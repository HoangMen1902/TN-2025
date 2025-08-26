import torch
import clip
from PIL import Image
import os
import sys
import gc
import json

yolo_model = torch.hub.load('ultralytics/yolov5', 'yolov5s', pretrained=True)
device = "cuda" if torch.cuda.is_available() else "cpu"
model, preprocess = clip.load("ViT-B/32", device=device)

def image_to_feature(image):
    if isinstance(image, str):
        image = Image.open(image).convert("RGB")
    image = preprocess(image).unsqueeze(0).to(device)
    with torch.no_grad():
        features = model.encode_image(image)
    return features.squeeze(0)


def detect_and_crop(image_path, target_label="book"):
    results = yolo_model(image_path)
    df = results.pandas().xyxy[0]  

    crops = []
    image = Image.open(image_path).convert("RGB")

    for idx, row in df.iterrows():
        label = row['name']  
        if label == target_label:  
            xmin, ymin, xmax, ymax = map(int, [row.xmin, row.ymin, row.xmax, row.ymax])
            crop = image.crop((xmin, ymin, xmax, ymax))
            crops.append(crop)
    return crops

def cache_folder_features(folder_path, cache_path):
    cache = {}
    images = []
    file_names = []

    for fname in os.listdir(folder_path):
        full_path = os.path.join(folder_path, fname)
        if not os.path.isfile(full_path):
            print(f"Bỏ qua thư mục: {fname}")
            continue
        if not fname.lower().endswith(('.jpg', '.jpeg', '.png', '.webp')):
            print(f"Bỏ qua file không phải ảnh: {fname}")
            continue

        try:
            image = preprocess(Image.open(full_path)).unsqueeze(0)
            images.append(image)
            file_names.append(fname)
        except Exception as e:
            print(f"Bỏ qua {fname}: {e}")

    if not images:
        print("Không có ảnh hợp lệ để cache.")
        return

    image_batch = torch.cat(images).to(device)
    with torch.no_grad():
        features = model.encode_image(image_batch).cpu()

    for fname, feat in zip(file_names, features):
        cache[fname] = feat
        print(f"Đã cache: {fname}")

    torch.save(cache, cache_path)
    print(f"Đã lưu cache vào: {cache_path}")

    del images, features, image_batch
    gc.collect()

def find_best_match_with_cache(input_img, cache_path, top_k=5):
    crops = detect_and_crop(input_img, target_label="book")

    if not crops:
        print(json.dumps([], ensure_ascii=False))
        return

    cache = torch.load(cache_path)
    if not cache:  
        print(json.dumps([], ensure_ascii=False))
        return

    results = []
    for crop in crops:
        crop_feat = image_to_feature(crop).cpu().squeeze(0)  

        scores = []
        for fname, feat in cache.items():
            feat = feat.squeeze(0)
            score = torch.nn.functional.cosine_similarity(crop_feat, feat, dim=0).item()
            scores.append((fname, score))

        scores = sorted(scores, key=lambda x: x[1], reverse=True)
        top_results = [{"file": fname, "score": score} for fname, score in scores[:top_k]]

        results.append(top_results)

    print(json.dumps(results, ensure_ascii=False))


if __name__ == "__main__":
    if len(sys.argv) < 3:
        print("Sai cú pháp. Dùng:\n  python find.py cache <folder_path> [cache_path]\n  python find.py match <input_img> [cache_path]")
        sys.exit(1)

    mode = sys.argv[1].lower()

    if mode == "cache":
        folder = sys.argv[2]
        cache_path = sys.argv[3] if len(sys.argv) > 3 else "features_cache.pt"
        cache_folder_features(folder, cache_path)

    elif mode == "match":
        input_img = sys.argv[2]
        cache_path = sys.argv[3] if len(sys.argv) > 3 else "features_cache.pt"
        find_best_match_with_cache(input_img, cache_path)

    else:
        print("Sai cú pháp. Dùng:\n  python find.py cache <folder_path> [cache_path]\n  python find.py match <input_img> [cache_path]")