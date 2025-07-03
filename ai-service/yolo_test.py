from ultralytics import YOLO
import cv2

# Load a pre-trained YOLOv11 model
# The 'n' stands for 'nano', the smallest and fastest version.
# The model will be downloaded automatically on first run.
model = YOLO("yolo11n.pt") 

# Define the path to your test image
image_path = "classroom_test.jpg"

print(f"--- Running YOLOv11 on '{image_path}' ---")

# Perform inference on the image
results = model(image_path)

# The 'results' object contains all the detection information.
# We will draw the results on the image.
annotated_frame = results[0].plot()

print("--- Detections ---")
# Loop through the detected boxes
for box in results[0].boxes:
    # Get the class name from the model's 'names' dictionary
    class_name = model.names[int(box.cls)]
    confidence = box.conf[0]
    print(f"Detected '{class_name}' with confidence {confidence:.2f}")

# Display the annotated image
cv2.imshow("YOLOv11 Detections", annotated_frame)

# Save the annotated image to a file
cv2.imwrite("classroom_test_output.jpg", annotated_frame)
print("\nOutput image saved as 'classroom_test_output.jpg'")

print("Press any key to exit.")
cv2.waitKey(0) # Wait indefinitely for a key press
cv2.destroyAllWindows()