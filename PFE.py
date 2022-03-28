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
#    Version       : 3.0
#    Created       : 18/03/2022
#    License       : GNU General v3.0
#    Developers    : BOUELKHEIR Yassine, CHENAFI Soumia
##

import mysql.connector
import serial
import time
import threading
import os

arduino_serial = serial.Serial('/dev/ttyAMA0', 9600, timeout=1)
arduino_serial.flush()

db = mysql.connector.connect(host="localhost", user="adminpi", password="adminpi", database='PFE')

dbrows = 130*[0]

def receiverHandler():
	while True:
		line = arduino_serial.readline().decode('utf-8').rstrip()
		datasplitted = line.split(" ")

		if datasplitted[0] == 'setsensor':
			cursor = db.cursor()
			sql = "INSERT INTO SENSORS (ID, VALUE, UNIXDATE) VALUES ('"+ datasplitted[1] +"', '" + datasplitted[2] +"', " + time.time() + ")"
			cursor.execute(sql)
			db.commit()
			dbrows[datasplitted[1]] += 1
			if dbrows[datasplitted[1]] == 10:
				cursor = db.cursor()
				sql = "DELETE FROM SENSORS WHERE ID = '"+ datasplitted[1] +"' ORDER BY UNIXDATE ASC LIMIT 1"
				cursor.execute(sql)
				db.commit()
				dbrows -= 1

		elif datasplitted[0] == 'setcharge':
			cursor = db.cursor()
			sql = "UPDATE CHARGES SET VALUE = '"+ datasplitted[2] +"' WHERE ID = '" + datasplitted[1] +"'"
			cursor.execute(sql)
			db.commit()
		time.sleep(0.05);

def broadcastHandler():
	while True:
		cursor = db.cursor()
		cursor.execute("SELECT ID, VALUE FROM CHARGES ORDER BY `ID` ASC")
		result = cursor.fetchall()
		for row in result:
			arduino_serial.write(str.encode("setcharge " + str(row[0]) + " " +  str(row[1])));

		time.sleep(0.05);

if __name__ == "__main__":
	
	reciever = threading.Thread(target=receiverHandler)
	broadcast = threading.Thread(target=broadcastHandler)
	
	print("Reciever handler is starting....");
	reciever.start()
	print("Reciever: OK");
	print("Broadcast handler is starting...");
	broadcast.start()
	print("Broadcast: OK");
	print("Data Logger v1.0 python script - PFE 2021/2022");
	reciever.join()
	broadcast.join()
