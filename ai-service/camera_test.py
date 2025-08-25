import cv2

print("Attempting to open webcam...")
cap = cv2.VideoCapture(0)

if not cap.isOpened():
    print("FATAL ERROR: Could not open video stream from webcam.")
    exit()
else:
    print("Webcam successfully opened. Press 'q' in the video window to exit.")

while True:
    success, frame = cap.read()
    if not success:
        break

    cv2.imshow("Webcam Test", frame)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
print("Resources released.")