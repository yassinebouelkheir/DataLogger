/**
   Copyright (c) 2022 Data Logger

   This program is free software: you can redistribute it and/or modify it under the terms of the
   GNU General Public License as published by the Free Software Foundation, either version 3 of the
   License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
   even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
   General Public License for more details.

   You should have received a copy of the GNU General Public License along with this program.
   If not, see <http://www.gnu.org/licenses/>.
*/

/*
   ScriptName    : Arduino_Smarthouse_Center.ino
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 01/06/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine
*/

#include <SPI.h>
#include <DHT.h>
#include <DHT_U.h>
#include <printf.h>
#include <string.h>
#include <Keypad.h>
#include <Servo.h>
#include "CO2Sensor.h"

#define DHTTYPE DHT11

DHT dhtext(4, DHTTYPE);
DHT dhtint(5, DHTTYPE);

bool cO2LevelHigh = false;
bool cO2WindowClosed = true; 

CO2Sensor co2Sensor(A1, 0.99, 100);

const byte ROWS = 4; //four rows
const byte COLS = 3; //three columns
char keys[ROWS][COLS] = {
   {'1','2','3'},
   {'4','5','6'},
   {'7','8','9'},
   {'*','0','#'}
};
byte rowPins[ROWS] = {25, 24, 23, 22};
byte colPins[COLS] = {28, 27, 26}; 

Keypad kpd = Keypad(makeKeymap(keys), rowPins, colPins, ROWS, COLS);

int keyCount = 0;
char keyString[5] = "53678";
char keyCumuled[5];

unsigned long closeDoorTimer = 0;

Servo myservo;

void setup() 
{
   kpd.setDebounceTime(10);
   for(int i = 22; i < 29; i++) pinMode(i, OUTPUT);
   pinMode(2, OUTPUT);
   pinMode(3, OUTPUT);

   digitalWrite(3, LOW);
   digitalWrite(2, LOW);
   noTone(2);

   pinMode(4, INPUT_PULLUP);
   pinMode(5, INPUT_PULLUP);

   pinMode(6, OUTPUT);
   pinMode(7, OUTPUT);
   pinMode(8, OUTPUT);
   pinMode(9, OUTPUT);

   myservo.attach(9);
   myservo.write(90);
    
   dhtext.begin();
   dhtint.begin();
   co2Sensor.calibrate();

   Serial.begin(9600);
}

void loop() 
{  
   if(closeDoorTimer < millis()) {
      Serial.println("setsensor 21 0");
      closeDoor();
   }

   char key = kpd.getKey();
   if(key)
   {
      keyCumuled[keyCount] = key;
      keyCount++;
      if(keyCount == 4)
      {
         if(keyCumuled == keyString)
         {
            Serial.println("setsensor 21 1");
            tone(2, 5000);
            delay(100);
            noTone(2);
            delay(100);
            tone(2, 5000);
            delay(100);
            noTone(2);
            openDoor();
            closeDoorTimer = millis() + 10000;
         }
         else tone(2, 500, 1000);
         keyCumuled[] = "";
         keyCount = 0;
      }
      else tone(2, 5000, 100);
   }

   float tempext = dhtext.readTemperature();
   Serial.println("setsensor 14 " + String(tempext));
   float tempint = dhtint.readTemperature();
   Serial.println("setsensor 15 " + String(tempint));
   float humidityint = 100 - dhtint.readHumidity();
   Serial.println("setsensor 16 " + String(humidityint));

   if(tempint > 24)
   {
      if(tempext < 24)
      {
         if(humidityint < 60) // FAN
         {
            Serial.println("setsensor 22 1");
            Serial.println("setsensor 23 0");
            Serial.println("setsensor 24 1");
            digitalWrite(6, LOW);
            digitalWrite(7, HIGH);
            digitalWrite(8, HIGH);
         }
         else 
         {
            Serial.println("setsensor 22 1");
            Serial.println("setsensor 23 0");
            Serial.println("setsensor 24 0");
            digitalWrite(6, LOW);
            digitalWrite(7, LOW);
            digitalWrite(8, HIGH);
         }
      }
      else
      {
         Serial.println("setsensor 22 0");
         Serial.println("setsensor 23 1");
         Serial.println("setsensor 24 1");
         digitalWrite(6, HIGH);
         digitalWrite(7, HIGH);
         if(!cO2LevelHigh) digitalWrite(8, LOW);   
      }
   }
   else
   {
      if(humidityint < 60) 
      {
         Serial.println("setsensor 22 0");
         Serial.println("setsensor 23 0");
         Serial.println("setsensor 24 1");
         digitalWrite(6, LOW);
         digitalWrite(7, HIGH);
         if(!cO2LevelHigh) digitalWrite(8, LOW);
      }
      else 
      {
         Serial.println("setsensor 22 0");
         Serial.println("setsensor 23 0");
         Serial.println("setsensor 24 0");
         digitalWrite(6, LOW);
         digitalWrite(7, LOW);
         if(!cO2LevelHigh) digitalWrite(8, LOW);
      }
   }

   float brightness = analogRead(A0);
   Serial.println("setsensor 19 " + String(brightness));
   if(brightness < 210)
   {
      digitalWrite(3, HIGH);
      Serial.println("setsensor 20 1");
   }
   else 
   {
      digitalWrite(3, LOW);   
      Serial.println("setsensor 20 0"); 
   }    

   float cO2Level = co2Sensor.read();
   
   Serial.println("setsensor 17 " + String(cO2Level));
   if(cO2Level > 600) 
   {
      Serial.println("setsensor 22 1");
      cO2LevelHigh = true;
      cO2WindowClosed = false;
      digitalWrite(8, HIGH);
   }         
   else
   {
      Serial.println("setsensor 22 0");
      cO2LevelHigh = false;
      if(cO2WindowClosed == false) {
         digitalWrite(8, LOW);
         cO2WindowClosed = true;
      }
   }
}

void openDoor()
{
   myservo.write(0); 
   return;
}

void closeDoor()
{
   myservo.write(90); 
   return;
}
