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

db = mysql.connector.connect(host="192.168.1.100", user="adminpi", password="adminpi", database='PFE') 
arduino = serial.Serial("/dev/ttyACM0", 9600, timeout=1)

lastquerytime = 1.0
queryCount = 0
def receiverHandler():
	global lastquerytime
	global queryCount
	print('recieverHandler Running. Press CTRL-C to exit.')
	time.sleep(0.1) 
	if arduino.isOpen():
		print("{} connected!".format(arduino.port))
		time.sleep(5)
		try:
			while True:
				#arduino.write(str.encode("OK"))
				while arduino.inWaiting()==0: pass
				if  arduino.inWaiting()>0: 
					answer=arduino.readline()
					decodedanswer = answer.decode(errors='ignore').replace('\n', '')
					print("A: " + decodedanswer)
					time.sleep(0.02)
					datasplitted = decodedanswer.split(' ')
					
					if datasplitted[0] == 'setsensor':
						if (type(datasplitted[1]) == int or float) and (type(datasplitted[2]) == int or float):
							if time.time() < lastquerytime:
								cursor = db.cursor(buffered=True)
								try: 
									cursor.execute("UPDATE `SENSORS_STATIC` SET VALUE = "+ str(datasplitted[2]) +" WHERE ID = " + str(datasplitted[1]))
								except:
									pass
								db.commit()

							else:
								cursor = db.cursor(buffered=True)
								try: 
									cursor.execute("UPDATE `SENSORS_STATIC` SET VALUE = "+ str(datasplitted[2]) +" WHERE ID = " + str(datasplitted[1]))
								except:
									pass
								db.commit()
								time.sleep(0.01)		
								cursor = db.cursor(buffered=True)
								try:
									cursor.execute("INSERT INTO `SENSORS` (ID, VALUE, UNIXDATE) VALUES ("+ str(datasplitted[1]) +", " + str(datasplitted[2]) +", " + str(time.time()) + ")")
								except:
									pass
								db.commit()
								cursor = db.cursor(buffered=True)
								cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 8 LIMIT 1")
								db.commit()
								result = cursor.fetchall()
								for row in result:
									if queryCount == 5:
										lastquerytime = time.time() + row[0]*60
										queryCount = 0
										break;
									elif queryCount < 5:
										queryCount += 1
								time.sleep(0.01)						
		except KeyboardInterrupt:
			print("KeyboardInterrupt has been caught.")

if __name__ == "__main__":

	reciever = threading.Thread(target=receiverHandler)
	reciever.start()

	print("Data Logger v2.0 python script - PFE 2021/2022");

	reciever.join()
