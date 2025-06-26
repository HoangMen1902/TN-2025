import torch
import clip
from PIL import Image
import os
import sys

device = "cuda" if torch.cuda.is_available() else "cpu"
model, preprocess = clip.load("ViT-B/32", device=device)

def image_to_feature(img_path):
    image = preprocess(Image.open(img_path)).unsqueeze(0).to(device)
    with torch.no_grad():
        features = model.encode_image(image)
    return features

def find_best_match(input_img, folder_path):
    input_feat = image_to_feature(input_img)
    best_score = -1
    best_fname = None

    for fname in os.listdir(folder_path):
        try:
            full_path = os.path.join(folder_path, fname)
            feat = image_to_feature(full_path)
            score = torch.cosine_similarity(input_feat, feat).item()
            if score > best_score:
                best_score = score
                best_fname = fname
        except:
            continue

    print(best_fname)

if __name__ == "__main__":
    input_img = sys.argv[1]
    folder = sys.argv[2]
    find_best_match(input_img, folder)