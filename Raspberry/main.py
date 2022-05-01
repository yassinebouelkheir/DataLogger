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
#    Created       : 18/03/2022
#    License       : GNU General v3.0
#    Developers    : BOUELKHEIR Yassine 
##

import RPi.GPIO as GPIO
import mysql.connector
import serial
import time
import threading
import os

db = mysql.connector.connect(host="localhost", user="adminpi", password="adminpi", database='PFE') 
arduino = serial.Serial("/dev/ttyACM0", 9600, timeout=1)

lastquerytime = [0,0,0,0,0,0,0,0,0]

def getquerytime(x, y=0):
	global lastquerytime
	if y == 0
		if x == 1 : #DC
			return lastquerytime[0]
		elif x == 2: # DC
			return lastquerytime[0]
		elif x == 3: # AC
			return lastquerytime[1]
		elif x == 4: # AC
			return lastquerytime[1]
		elif x == 5: # Temp
			return lastquerytime[2]
		elif x == 6: # Temp
			return lastquerytime[2]
		elif x == 7: # Brightness
			return lastquerytime[3]
		elif x == 8: # Humidity
			return lastquerytime[4]
		elif x == 9: # Wind Speed 1
			return lastquerytime[5]
		elif x == 10: # Wind Speed 2
			return lastquerytime[5]
		elif x == 11: # Turbine
			return lastquerytime[5]
		elif x == 12: # Eo Tension DC
			return lastquerytime[6]
		elif x == 13: # Eo Courant DC
			return lastquerytime[6]

	elif y == 1
		if x == 1 : # DC
			cursor = db.cursor()
			cursor.execute("SELECT time FROM `updatetime` WHERE ID = 1 LIMIT 1")
			result = cursor.fetchall()
			for row in result:
				lastquerytime[0] = time.time() + row[0]*1000
				break;
		elif x == 2: # DC
			cursor = db.cursor()
			cursor.execute("SELECT time FROM `updatetime` WHERE ID = 1 LIMIT 1")
			result = cursor.fetchall()
			for row in result:
				lastquerytime[0] = time.time() + row[0]*1000
				break;
		elif x == 3: # AC
			cursor = db.cursor()
			cursor.execute("SELECT time FROM `updatetime` WHERE ID = 2 LIMIT 1")
			result = cursor.fetchall()
			for row in result:
				lastquerytime[1] = time.time() + row[0]*1000
				break;
		elif x == 4: # AC
			cursor = db.cursor()
			cursor.execute("SELECT time FROM `updatetime` WHERE ID = 2 LIMIT 1")
			result = cursor.fetchall()
			for row in result:
				lastquerytime[1] = time.time() + row[0]*1000
				break;
		elif x == 5: # Temp 1 
			cursor = db.cursor()
			cursor.execute("SELECT time FROM `updatetime` WHERE ID = 3 LIMIT 1")
			result = cursor.fetchall()
			for row in result:
				lastquerytime[2] = time.time() + row[0]*1000
				break;
		elif x == 6: # Temp 2
			cursor = db.cursor()
			cursor.execute("SELECT time FROM `updatetime` WHERE ID = 3 LIMIT 1")
			result = cursor.fetchall()
			for row in result:
				lastquerytime[2] = time.time() + row[0]*1000
				break;
		elif x == 7: # Brightness
			cursor = db.cursor()
			cursor.execute("SELECT time FROM `updatetime` WHERE ID = 4 LIMIT 1")
			result = cursor.fetchall()
			for row in result:
				lastquerytime[3] = time.time() + row[0]*1000
				break;
		elif x == 8: # Humidity
			cursor = db.cursor()
			cursor.execute("SELECT time FROM `updatetime` WHERE ID = 5 LIMIT 1")
			result = cursor.fetchall()
			for row in result:
				lastquerytime[4] = time.time() + row[0]*1000
				break;
		elif x == 9: # Wind Speed
			cursor = db.cursor()
			cursor.execute("SELECT time FROM `updatetime` WHERE ID = 6 LIMIT 1")
			result = cursor.fetchall()
			for row in result:
				lastquerytime[5] = time.time() + row[0]*1000
				break;
		elif x == 10: # Wind Speed
			cursor = db.cursor()
			cursor.execute("SELECT time FROM `updatetime` WHERE ID = 6 LIMIT 1")
			result = cursor.fetchall()
			for row in result:
				lastquerytime[5] = time.time() + row[0]*1000
				break;
		elif x == 11: # Turbine
			cursor = db.cursor()
			cursor.execute("SELECT time FROM `updatetime` WHERE ID = 6 LIMIT 1")
			result = cursor.fetchall()
			for row in result:
				lastquerytime[5] = time.time() + row[0]*1000
				break;
		elif x == 12: # EO Tension DC
			cursor = db.cursor()
			cursor.execute("SELECT time FROM `updatetime` WHERE ID = 7 LIMIT 1")
			result = cursor.fetchall()
			for row in result:
				lastquerytime[6] = time.time() + row[0]*1000
				break;
		elif x == 13: # EO Courant DC
			cursor = db.cursor()
			cursor.execute("SELECT time FROM `updatetime` WHERE ID = 7 LIMIT 1")
			result = cursor.fetchall()
			for row in result:
				lastquerytime[6] = time.time() + row[0]*1000
				break;

def receiverHandler():
	global lastquerytime
	print('Running. Press CTRL-C to exit.')
	time.sleep(0.1) 
	if arduino.isOpen():
		print("{} connected!".format(arduino.port))
		time.sleep(5)
		try:
			while True:
				cursor = db.cursor(buffered=True)
				cursor.execute("SELECT ID, VALUE FROM `CHARGES` WHERE 1 ORDER BY `ID` ASC")
				result = cursor.fetchall()
				for row in result:
					arduino.write(str.encode("setcharge " + str(row[0]) + " " +  str(row[1]) + "\n"));
					print("R: setcharge " + str(row[0]) + " " +  str(row[1]));

				while arduino.inWaiting()==0: pass
				if  arduino.inWaiting()>0: 
					answer=arduino.readline()
					decodedanswer = answer.decode(errors='ignore').replace('\n', '')
					print("A: " + decodedanswer)
					time.sleep(0.02)
					datasplitted = decodedanswer.split(' ')
					
					if datasplitted[0] == 'setsensor':
						if time.time() < getquerytime(datasplitted[1]):
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
							getquerytime(datasplitted[1], 1)
							time.sleep(0.01)						
		except KeyboardInterrupt:
			print("KeyboardInterrupt has been caught.")
			

if __name__ == "__main__":
	
	GPIO.setmode(GPIO.BCM)
	GPIO.setwarnings(False)
	GPIO.setup(40, GPIO.OUT)
	GPIO.output(40, GPIO.LOW)
	reciever = threading.Thread(target=receiverHandler)
	reciever.start()
	print("Data Logger v2.0 python script - PFE 2021/2022");
	GPIO.output(40, GPIO.HIGH)
	reciever.join()
