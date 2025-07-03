import cv2

# Initialize the video capture object. 0 is usually the default webcam.
cap = cv2.VideoCapture(0)

if not cap.isOpened():
    print("Error: Could not open video stream from webcam.")
    exit()

print("Camera test successful! Press 'q' to quit.")

# Loop to continuously get frames from the webcam
while True:
    # Capture frame-by-frame
    ret, frame = cap.read()

    # If frame is read correctly, ret is True
    if not ret:
        print("Error: Can't receive frame (stream end?). Exiting ...")
        break

    # Display the resulting frame in a window
    cv2.imshow('Camera Test - Press Q to Quit', frame)

    # Wait for the 'q' key to be pressed to exit the loop
    if cv2.waitKey(1) == ord('q'):
        break

# When everything is done, release the capture object and close windows
cap.release()
cv2.destroyAllWindows()
print("Resources released.")