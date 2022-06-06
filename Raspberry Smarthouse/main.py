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
#    ScriptName    : main.py
#    Author        : BOUELKHEIR Yassine
#    Version       : 2.0
#    Created       : 03/06/2022
#    License       : GNU General v3.0
#    Developers    : BOUELKHEIR Yassine 
##

import RPi.GPIO as GPIO
import mysql.connector
import serial
import time
import threading
import os

db = mysql.connector.connect(host="192.168.0.100", user="adminpi", password="adminpi", database='PFE') 
arduino = serial.Serial("/dev/ttyACM0", 9600, timeout=1)

lastquerytime = 1.0

def receiverHandler():
	global lastquerytime
	fixedRate = 1.0
	print('recieverHandler Running. Press CTRL-C to exit.')
	time.sleep(0.1) 
	if arduino.isOpen():
		print("{} connected!".format(arduino.port))
		time.sleep(5)
		try:
			while True:
				arduino.write(str.encode("R: OK"))
				while arduino.inWaiting()==0: pass
				if  arduino.inWaiting()>0: 
					answer=arduino.readline()
					decodedanswer = answer.decode(errors='ignore').replace('\n', '')
					print("A: " + decodedanswer)
					time.sleep(0.02)
					datasplitted = decodedanswer.split(' ')
					
					if datasplitted[0] == 'setsensor':
						if time.time() < lastquerytime:
							cursor = db.cursor(buffered=True)
							cursor.execute("UPDATE `SENSORS_STATIC` SET VALUE = "+ str(datasplitted[2]) +" WHERE ID = " + str(datasplitted[1]))
							db.commit()

						else:
							cursor = db.cursor(buffered=True)
							cursor.execute("UPDATE `SENSORS_STATIC` SET VALUE = "+ str(datasplitted[2]) +" WHERE ID = " + str(datasplitted[1]))
							db.commit()
							time.sleep(0.01)		
							cursor = db.cursor(buffered=True)
							cursor.execute("INSERT INTO `SENSORS` (ID, VALUE, UNIXDATE) VALUES ("+ str(datasplitted[1]) +", " + str(datasplitted[2]) +", " + str(time.time()) + ")")
							db.commit()
							lastquerytime = time.time() + (fixedRate*60)
							time.sleep(0.01)						
		except KeyboardInterrupt:
			print("KeyboardInterrupt has been caught.")

if __name__ == "__main__":
	
	GPIO.setmode(GPIO.BCM)
	GPIO.setwarnings(False)
	GPIO.setup(21, GPIO.OUT)
	GPIO.output(21, GPIO.LOW)

	reciever = threading.Thread(target=receiverHandler)
	reciever.start()

	print("Data Logger v2.0 python script - PFE 2021/2022");
	GPIO.output(21, GPIO.HIGH)

	reciever.join()
