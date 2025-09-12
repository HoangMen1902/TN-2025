import mysql.connector;
from rapidfuzz import fuzz;
from rapidfuzz import process;
from rapidfuzz import process;
from dotenv import load_dotenv
import os


load_dotenv();


cnx = mysql.connector.connect(
    host=os.getenv('DB_HOST'),
    port=os.getenv('DB_PORT'),
    user=os.getenv('DB_USERNAME'),
    password=os.getenv('DB_PASSWORD'),
    database=os.getenv('DB_DATABASE'));


cur = cnx.cursor(dictionary=True)

cur.execute('SELECT id FROM providers WHERE provider_name LIKE %s LIMIT 1', ('%Viettel Post%',))
viettel_fetch = cur.fetchone()
if(viettel_fetch):
    viettel_post_id = viettel_fetch['id']
else:
    raise Exception("Không tìm thấy nhà cung cấp Viettel Post")


cur.execute("UPDATE provider_provinces SET province_id = NULL WHERE provider_id = %s", [viettel_post_id]);
cur.execute("UPDATE provider_districts SET district_id = NULL WHERE provider_id = %s", [viettel_post_id]);
cur.execute("UPDATE provider_wards SET ward_id = NULL WHERE provider_id = %s", [viettel_post_id]);

cur.execute('SELECT id, name FROM provinces');


province_data = cur.fetchall();

cur.execute('SELECT id, provider_province_name FROM provider_provinces WHERE province_id IS NULL AND provider_id = %s', [viettel_post_id]);
provider_provinces = cur.fetchall();


def clean_prefix(name):
    for prefix in ['TT ', 'KCN ', 'KCN -', 'KHU CÔNG NGHIỆP ',
                   'KHU CN ', 'ẤP ', 'CHỢ ', 'THỊ TRẤN ']:
        if name.upper().startswith(prefix): 
            return name[len(prefix):].strip()
    return name.strip()

internal_names = [p['name'] for p in province_data];

for row in provider_provinces:
    match, score, idx = process.extractOne(
        row['provider_province_name'], internal_names, scorer=fuzz.ratio
    )
    if score > 70:  
        matched_province_id = province_data[idx]['id']
        cur.execute("UPDATE provider_provinces SET province_id = %s WHERE id = %s", (matched_province_id, row['id']));
    else:
        print(f"[??] Không tìm được tỉnh phù hợp cho '{row['provider_province_name']}' (score={score})");



cur.execute("SELECT d.id, d.name AS district_name, p.name AS province_name FROM districts d JOIN provinces p ON p.id = d.province_id")
district_data = cur.fetchall()

cur.execute("""
    SELECT pd.id, pd.provider_district_name AS district_name, 
           pv.provider_province_name AS province_name 
    FROM provider_districts pd 
    JOIN provider_provinces pv ON pd.provider_province_code = pv.provider_province_code 
    WHERE district_id IS NULL AND pd.provider_id = %s
""", [viettel_post_id])
provider_districts = cur.fetchall()

full_district_refs = [
    {
        'id': d['id'],
        'district': d['district_name'].upper(),
        'province': d['province_name'].upper(),
        'full_name': f"{d['district_name'].upper()}, {d['province_name'].upper()}"
    }
    for d in district_data
]


full_names = [ref['full_name'] for ref in full_district_refs]




with open("output.txt", "w", encoding="utf-8") as f:
    for row in provider_districts:
        provider_district = row['district_name'].upper()
        provider_province = row['province_name'].upper()
        provider_full = f"{provider_district}, {provider_province}"

        match_name, score, idx = process.extractOne(provider_full, full_names, scorer=fuzz.ratio)
        if score > 78:
            matched = full_district_refs[idx]
            cur.execute("UPDATE provider_districts SET district_id = %s WHERE id = %s", (matched['id'], row['id']))
        else:
            district_names = [d['district'] for d in full_district_refs]
            match_district, district_score, idx2 = process.extractOne(provider_district, district_names, scorer=fuzz.ratio)

            matched = full_district_refs[idx2]
            cur.execute("UPDATE provider_districts SET district_id = %s WHERE id = %s", (matched['id'], row['id']))
            print(f" Không khớp đúng tỉnh. Gán tạm '{provider_district}' với '{matched['district']}, {matched['province']}' (score={district_score})")

            suggestions = process.extract(provider_district, district_names, scorer=fuzz.ratio, limit=3)
            print(f"   Gợi ý khác:")
            for sug, sug_score, _ in suggestions:
                print(f"    - {sug} (score={sug_score})")
        

cur.execute("SELECT w.id, w.name, d.name as district_name FROM wards w JOIN districts d ON d.id = w.district_id");
ward_data = cur.fetchall();

cur.execute('SELECT pw.id, pw.provider_ward_name, pd.provider_district_name AS district_name FROM provider_wards pw JOIN provider_districts pd ON pd.provider_district_code = pw.provider_district_code WHERE pw.ward_id IS NULL AND pw.provider_id = %s', [viettel_post_id]);
provider_ward = cur.fetchall();


full_wards_ref = [
    {
        'id': d['id'],
        'ward': clean_prefix(d['name']).upper(),
        'district': d['district_name'].upper(),
        'full_name': f"{clean_prefix(d['name']).upper()}, {d['district_name'].upper()}"
    }
    for d in ward_data
]

full_ward_name = [w_ref['full_name'] for w_ref in full_wards_ref];

with open("output.txt", "w", encoding="utf-8") as f:
    for row in provider_ward:
        provider_ward_name = row['provider_ward_name']
        provider_district = row['district_name']
        provider_full = f"{provider_ward_name}, {provider_district}"

        match_name, score, idx = process.extractOne(provider_full, full_ward_name, scorer=fuzz.ratio)

        if score > 80:
            matched = full_wards_ref[idx]
            cur.execute("UPDATE provider_wards SET ward_id = %s WHERE id = %s", (matched['id'], row['id'])) 
            f.write(f"Da gan {provider_ward_name} cho {match_name}, score {score}\n")
        else:
            f.write(f"[failed] {provider_full} -> (best score: {score})\n")

cnx.commit()
cnx.close()