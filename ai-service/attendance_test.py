from deepface import DeepFace

# Define the file paths
img_to_check_path = "test_image.jpg"
img_registered_path = "database/students/KwekCheeHao.jpg" 

print(f"--- Verifying '{img_to_check_path}' against '{img_registered_path}' ---")

try:
    # We are adding the distance_metric parameter here
    result = DeepFace.verify(
        img1_path = img_to_check_path,
        img2_path = img_registered_path,
        model_name="Facenet512",
        distance_metric="euclidean_l2" # ADD THIS LINE
    )

    print("\n--- Verification Result ---")
    print(result)

    if result["verified"]:
        print("\nSUCCESS: The images are a match!")
    else:
        print(f"\nFAILURE: The images are NOT a match. Distance ({result['distance']:.4f}) is above the threshold ({result['threshold']:.2f}).")

except ValueError as e:
    print(f"\n--- An Error Occurred ---")
    print(f"Error: {e}")