# File: simple_video_streamer.py
from flask import Flask, Response
import cv2
import json
import os
import time

# --- 1. CONFIGURATION ---
VIDEO_PATH = r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\classroom_video_3.mp4"
# We need to know the class names to draw the labels
CLASS_NAMES = {0: 'Attentive', 1: 'Not-Attentive'} # IMPORTANT: CHECK if this matches your model (0 might be Not-Attentive)

app = Flask(__name__)

# --- 2. STREAMING FUNCTION ---
def stream_with_boxes():
    cap = cv2.VideoCapture(VIDEO_PATH)
    if not cap.isOpened():
        print("STREAMER ERROR: Could not open video file.")
        return

    print("STREAMER: Started streaming video with detections.")

    while cap.isOpened():
        success, frame = cap.read()
        if not success:
            break
        
        detections = []
        # Check if the detection file exists and is not empty
        if os.path.exists('temp_detections.json') and os.path.getsize('temp_detections.json') > 0:
            try:
                with open('temp_detections.json', 'r') as f:
                    detections = json.load(f)
            except (json.JSONDecodeError, FileNotFoundError):
                # This can happen if the file is being written at the exact same time
                # We just skip this frame and use the previous detections
                pass

        # If the file is empty, it means the detector finished
        if not detections:
             # You could add a 'Session Ended' text on the frame here if you want
             pass

        # Draw the boxes from the data we read from the file
        for det in detections:
            x1, y1, x2, y2, conf, cls_id = det
            class_name = CLASS_NAMES.get(int(cls_id), 'Unknown')
            
            # Your drawing logic
            color = (0, 255, 0) if class_name.lower() == 'attentive' else (0, 0, 255)
            cv2.rectangle(frame, (int(x1), int(y1)), (int(x2), int(y2)), color, 2)
            label = f"{class_name}: {conf:.2f}"
            cv2.putText(frame, label, (int(x1), int(y1) - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.7, color, 2)
            
        # Encode and yield the frame for the browser
        ret, buffer = cv2.imencode('.jpg', frame)
        if not ret:
            continue
            
        frame_bytes = buffer.tobytes()
        yield (b'--frame\r\n'
               b'Content-Type: image/jpeg\r\n\r\n' + frame_bytes + b'\r\n')
        
        # Delay to approximate real-time playback
        time.sleep(1/30) # ~30 FPS
        
    cap.release()
    print("STREAMER: Video finished, stopping stream.")

# --- 3. FLASK ROUTE ---
@app.route('/video_feed')
def video_feed():
    return Response(stream_with_boxes(), mimetype='multipart/x-mixed-replace; boundary=frame')

# --- 4. START SERVER ---
if __name__ == '__main__':
    print("STREAMER: Starting Flask server at http://127.0.0.1:5001/video_feed")
    app.run(host='0.0.0.0', port=5001)