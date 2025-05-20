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

cur.execute("UPDATE provider_provinces SET province_id = NULL");
cur.execute("UPDATE provider_districts SET district_id = NULL");
cur.execute("UPDATE provider_wards SET ward_id = NULL");

cur.execute('SELECT id, name FROM provinces');


province_data = cur.fetchall();

cur.execute('SELECT id, provider_province_name FROM provider_provinces WHERE province_id IS NULL');
provider_provinces = cur.fetchall();


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



cur.execute("SELECT id, name FROM districts");
district_data = cur.fetchall();

cur.execute('SELECT id, provider_district_name FROM provider_districts WHERE district_id IS NULL');
provider_district = cur.fetchall();

internal_district = [d['name'].upper() for d in district_data];

for row in provider_district:
    match, score, idx = process.extractOne(row['provider_district_name'], internal_district, scorer=fuzz.ratio)
    if score > 70:
        matched_district_id = district_data[idx]['id']
        cur.execute("UPDATE provider_districts SET district_id = %s WHERE id = %s", (matched_district_id, row['id']));
    else: 
        suggestions = process.extract(
            row['provider_district_name'].lower(), 
            internal_district, 
            scorer=fuzz.ratio,
            limit=3
        )
        print(f"[??] Không tìm được quận/huyện phù hợp cho '{row['provider_district_name']}' (score={score})")
        print(" → Gợi ý gần đúng:")
        for sug_name, sug_score, _ in suggestions:
            print(f"    - {sug_name} (score={sug_score})")
        
        
cur.execute("SELECT id, name FROM wards");
ward_data = cur.fetchall();

cur.execute('SELECT id, provider_ward_name FROM provider_wards WHERE ward_id IS NULL');
provider_ward = cur.fetchall();

def clean_prefix(name):
    for prefix in ['TT ', 'TX ', 'KCN ', 'KCN -', 'KHU CÔNG NGHIỆP ',
                   'KHU CN ', 'ẤP ', 'CHỢ ', 'PHƯỜNG ', 'XÃ ', 'THỊ TRẤN ']:
        if name.upper().startswith(prefix):
            return name[len(prefix):].strip()
    return name.strip()

internal_ward = [
    clean_prefix(d['name']).upper()
    for d in ward_data
]
        
def clean_prefix(name):
    for prefix in ['TT ', 'TX ', 'KCN ', 'KCN -', 'KHU CÔNG NGHIỆP ',
                   'KHU CN ', 'ẤP ', 'CHỢ ', 'PHƯỜNG ', 'XÃ ', 'THỊ TRẤN ']:
        if name.upper().startswith(prefix): 
            return name[len(prefix):].strip()
    return name.strip()

with open("output.txt", "w", encoding="utf-8") as f:
    for row in provider_ward:
        cleaned_name = clean_prefix(row['provider_ward_name']).upper()
        match, score, idx = process.extractOne(cleaned_name, internal_ward, scorer=fuzz.ratio)

        if score > 83:
            matched_ward_id = ward_data[idx]['id']
            cur.execute("UPDATE provider_wards SET ward_id = %s WHERE id = %s", (matched_ward_id, row['id']))
            print(f"[Phường/Xã][OK] Đã gắn '{row['provider_ward_name']}' cho '{ward_data[idx]['name']}', (score={score})", file=f)
        else:
            suggestions = process.extract(
                cleaned_name,
                internal_ward,
                scorer=fuzz.ratio,
                limit=3
            )
            
            print(f"[??] Không tìm được phường/xã phù hợp cho '{row['provider_ward_name']}' (score={score})", file=f)
            print(" Gợi ý gần đúng:", file=f)
            for sug_name, sug_score, _ in suggestions:
                print(f"    - {sug_name} (score={sug_score})", file=f)
cnx.commit()
cnx.close()