import cv2
import sys
from flask import Flask, render_template, Response
from webcamvideostream import WebcamVideoStream
import time
import threading

app = Flask(__name__)
last_epoch = 0


@app.route('/')
def index():
    return render_template('index.html')

def gen(camera):
    while True:
        frame = camera.read()
        ret, jpeg = cv2.imencode('.jpg',frame)
        if jpeg is not None:
            yield (b'--frame\r\n'
                   b'Content-Type: image/jpeg\r\n\r\n' + jpeg.tobytes() + b'\r\n\r\n')
        else:
            print("frame is none")

@app.route('/video_feed')
def video_feed():
    return Response(gen(WebcamVideoStream().start()),
                    mimetype='multipart/x-mixed-replace; boundary=frame')

if __name__ == '__main__':
    app.run(host='0.0.0.0', debug=True, threaded=True)
