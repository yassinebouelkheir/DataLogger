import cv2
from threading import Thread
import time
import numpy as np
from datetime import datetime

initilaized = 0
threadstarted = 0
firstself = 0
class WebcamVideoStream:
    def __init__(self, src = 0):
        global initilaized
        global firstself
        if initilaized == 0:
            initilaized = 1
            firstself = self
            print("init")
            firstself.stream = cv2.VideoCapture(src)
            (firstself.grabbed, firstself.frame) = firstself.stream.read()
            firstself.stopped = False
            time.sleep(2.0)
        elif initilaized != 0:
            print("init")
            (firstself.grabbed, firstself.frame) = firstself.stream.read()
            firstself.stopped = False
            time.sleep(2.0)
    
    def start(self):
        global threadstarted
        global firstself
        if threadstarted == 0:
            threadstarted = 1
            print("start thread")
            t = Thread(target=firstself.update, args=())
            t.daemon = True
            t.start()
        return self
    
    def update(self):
        global firstself
        print("read")
        while True:
            if firstself.stopped:
                return           
            (firstself.grabbed, firstself.frame) = firstself.stream.read()
            font = cv2.FONT_HERSHEY_SIMPLEX
            cv2.putText(firstself.frame, 'CAMERA 1', (10, 470), font, 0.5, (255, 255, 255), 1, cv2.LINE_4)
            cv2.putText(firstself.frame, datetime.today().strftime('%Y-%m-%d %H:%M:%S'), (445, 470), font, 0.5, (255, 255, 255), 1, cv2.LINE_4)
    
    def read(self):
        global firstself
        return firstself.frame
    
    def stop(self):
        global firstself
        firstself.stopped = True


