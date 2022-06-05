##
 # Copyright (c) 2022 Data Logger
 #
 # This program is free software: you can redistribute it and/or modify it under the terms of the
 # GNU General Public License as published by the Free Software Foundation, either version 3 of the
 # License, or (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 # even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 # General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License along with this program.
 # If not, see <http://www.gnu.org/licenses/>.
##

## 
#    ScriptName    : webcamvideostream.py
#    Author        : BOUELKHEIR Yassine
#    Version       : 2.0
#    Created       : 04/06/2022
#    License       : GNU General v3.0
#    Developers    : BOUELKHEIR Yassine 
##

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


