import os
import shutil
from collections import Counter

# --- Configuration ---
train_path = "My-First-Project-1/train"
images_path = os.path.join(train_path, "images")
labels_path = os.path.join(train_path, "labels")

# Class ID for the minority class ('Attentive')
minority_class_id = 0

# How many total copies we want. 2 means 1 original + 1 copy.
total_sets = 2 

# --- Functions ---
def get_class_counts(labels_dir):
    """Counts the instances of each class in the label files."""
    class_counts = Counter()
    for filename in os.listdir(labels_dir):
        if filename.endswith(".txt"):
            with open(os.path.join(labels_dir, filename), 'r') as f:
                for line in f:
                    try:
                        class_id = int(line.split()[0])
                        class_counts[class_id] += 1
                    except (ValueError, IndexError):
                        continue # Skip empty or malformed lines
    return class_counts

def find_minority_files(labels_dir, class_id):
    """Finds all label files that contain at least one instance of the minority class."""
    minority_files = []
    for filename in os.listdir(labels_dir):
        if filename.endswith(".txt"):
            with open(os.path.join(labels_dir, filename), 'r') as f:
                for line in f:
                    if int(line.split()[0]) == class_id:
                        minority_files.append(filename)
                        break
    return minority_files

# --- Main Script ---
print("--- Dataset Balancing Script ---")

# 1. Analyze initial state
print("Initial class counts in training set:")
initial_counts = get_class_counts(labels_path)
for class_id, count in initial_counts.items():
    print(f"  Class {class_id}: {count} instances")

# 2. Find files to duplicate
print(f"\nIdentifying files with minority class ID: {minority_class_id}")
files_to_duplicate = find_minority_files(labels_path, minority_class_id)
print(f"Found {len(files_to_duplicate)} unique images containing the minority class.")

# 3. Perform duplication
# We loop from 1 to create n-1 copies. If total_sets is 2, we create 1 copy.
for i in range(1, total_sets):
    print(f"\nCreating duplication set #{i}...")
    for label_filename in files_to_duplicate:
        base_name = os.path.splitext(label_filename)[0]
        # Handle Roboflow's complex filenames
        image_ext = ".jpg" # Assume .jpg, but could be others
        image_filename = base_name + image_ext
        
        # Define new filenames
        new_base_name = f"{base_name}_bal_copy{i}"
        new_label_filename = new_base_name + ".txt"
        new_image_filename = new_base_name + image_ext

        # Define source and destination paths
        src_label_path = os.path.join(labels_path, label_filename)
        dest_label_path = os.path.join(labels_path, new_label_filename)
        src_image_path = os.path.join(images_path, image_filename)
        dest_image_path = os.path.join(images_path, new_image_filename)

        # Copy the files if the source image exists
        if os.path.exists(src_image_path):
            shutil.copyfile(src_label_path, dest_label_path)
            shutil.copyfile(src_image_path, dest_image_path)
    print(f"Duplication set #{i} created successfully.")

# 4. Analyze final state
print("\nFinal class counts in training set after balancing:")
final_counts = get_class_counts(labels_path)
for class_id, count in final_counts.items():
    print(f"  Class {class_id}: {count} instances")

print("\n--- Balancing Complete ---")