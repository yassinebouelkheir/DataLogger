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
#    ScriptName    : PFE.py
#    Author        : BOUELKHEIR Yassine
#    Version       : 1.0
#    Created       : 18/03/2022
#    License       : GNU General v3.0
#    Developers    : BOUELKHEIR Yassine, CHENAFI Soumia
##

import RPi.GPIO as GPIO
import mysql.connector
import serial
import time
import threading
import os

db = mysql.connector.connect(host="localhost", user="adminpi", password="adminpi", database='PFE') 
arduino = serial.Serial("/dev/ttyACM0", 9600, timeout=1)

rowcounts = 22
lastquerytime = 0
addedrows = 0
def receiverHandler():
	global rowcounts
	global lastquerytime
	global addedrows 
	print('Running. Press CTRL-C to exit.')
	time.sleep(0.1) #wait for serial to open
	if arduino.isOpen():
		print("{} connected!".format(arduino.port))
		time.sleep(5)
		try:
			while True:
				cursor = db.cursor()
				cursor.execute("SELECT ID, VALUE FROM `CHARGES` WHERE ID = " + str(rowcounts))
				result = cursor.fetchall()
				rowcounts += 1
				if rowcounts == 26:
					rowcounts = 22
				for row in result:
					arduino.write(str.encode("setcharge " + str(row[0]) + " " +  str(row[1]) + "\n"));
					print("B: setcharge " + str(row[0]) + " " +  str(row[1]));

				while arduino.inWaiting()==0: pass
				if  arduino.inWaiting()>0: 
					answer=arduino.readline()
					decodedanswer = answer.decode(errors='ignore').replace('\n', '')
					print("R: " + decodedanswer)
					time.sleep(0.025)
					datasplitted = decodedanswer.split(' ')
					
					if datasplitted[0] == 'setsensor':
						if time.time() < lastquerytime:
							cursor = db.cursor(buffered=True)
							cursor.execute("UPDATE `SENSORS_STATIC` SET VALUE = "+ str(datasplitted[2]) +" WHERE ID = " + str(datasplitted[1]))
							db.commit()

						else:
							cursor = db.cursor(buffered=True)
							cursor.execute("UPDATE `SENSORS_STATIC` SET VALUE = "+ str(datasplitted[2]) +" WHERE ID = " + str(datasplitted[1]))
							time.sleep(0.01)		
							cursor = db.cursor(buffered=True)
							sql = "INSERT INTO `SENSORS` (ID, VALUE, UNIXDATE) VALUES ("+ str(datasplitted[1]) +", " + str(datasplitted[2]) +", " + str(time.time()) + ")"
							cursor.execute(sql)
							db.commit()
							addedrows += 1
							if addedrows == 6:
								lastquerytime = time.time()+120
								addedrows = 0
								
							time.sleep(0.025)
						
					elif datasplitted[0] == 'setcharge':
							cursor = db.cursor(buffered=True)
							sql = "UPDATE CHARGES SET VALUE = "+ str(datasplitted[2]) +" WHERE ID = " + str(datasplitted[1]) +""
							cursor.execute(sql)
							db.commit()
						
					time.sleep(0.05)
		except KeyboardInterrupt:
			print("KeyboardInterrupt has been caught.")
			

if __name__ == "__main__":
	
	GPIO.setmode(GPIO.BCM)
	GPIO.setwarnings(False)
	GPIO.setup(17, GPIO.OUT)
	GPIO.output(17, GPIO.LOW)
	reciever = threading.Thread(target=receiverHandler)
	reciever.start()
	print("Data Logger v1.0 python script - PFE 2021/2022");
	GPIO.output(17, GPIO.HIGH)
	reciever.join()
