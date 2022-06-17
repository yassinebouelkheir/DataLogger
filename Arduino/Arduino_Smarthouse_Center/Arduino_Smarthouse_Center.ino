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
#include <nRF24L01.h>
#include <printf.h>
#include <RF24.h>
#include <RF24_config.h>
#include <string.h>
#include <Keypad.h>
#include "CO2Sensor.h"

#define DHTTYPE DHT11

RF24 radio(9, 10);       
const byte address1[6] = "63257";

DHT dhtext(4, DHTTYPE);
DHT dhtint(5, DHTTYPE);

bool cO2LevelHigh = false;
bool cO2WindowClosed = true; 

CO2Sensor co2Sensor(A1, 0.99, 100);

void setup() 
{
   radio.begin();

   radio.openWritingPipe(address1);

   radio.setPALevel(RF24_PA_MAX); 
   radio.stopListening(); 

   for(int i = 22; i < 30; i++) pinMode(i, OUTPUT);
   pinMode(2, OUTPUT);
   pinMode(3, OUTPUT);
   digitalWrite(3, LOW);
   digitalWrite(2, LOW);
   noTone(2);
   pinMode(4, INPUT_PULLUP);
   pinMode(5, INPUT_PULLUP);
   dhtext.begin();
   dhtint.begin();
   co2Sensor.calibrate();
   Serial.begin(9600);
}

void loop() 
{
   char data[3];
   sprintf(data, "00");
   
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
            sprintf(data, "01");
            openWindow();
         }
         else 
         {
            Serial.println("setsensor 22 1");
            Serial.println("setsensor 23 0");
            Serial.println("setsensor 24 0");
            sprintf(data, "00"); 
            openWindow();
         }
      }
      else
      {
         if(humidityint < 60) 
         {
            Serial.println("setsensor 22 0");
            Serial.println("setsensor 23 1");
            Serial.println("setsensor 24 1");
            sprintf(data, "11"); // FAN & AC 
            if(!cO2LevelHigh) closeWindow();
         }
         else 
         {
            Serial.println("setsensor 22 0");
            Serial.println("setsensor 23 1");
            Serial.println("setsensor 24 0");
            sprintf(data, "10"); // AC
            if(!cO2LevelHigh) closeWindow();   
         }    
      }
   }
   else
   {
      if(humidityint < 60) 
      {
         Serial.println("setsensor 22 0");
         Serial.println("setsensor 23 0");
         Serial.println("setsensor 24 1");
         sprintf(data, "01"); // FAN
         if(!cO2LevelHigh) closeWindow();
      }
      else 
      {
         Serial.println("setsensor 22 0");
         Serial.println("setsensor 23 0");
         Serial.println("setsensor 24 0");
         sprintf(data, "00"); // NOTHING
         if(!cO2LevelHigh) closeWindow();
      }
   }
   radio.write(&data, sizeof(data));   

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
      openWindow();
   }         
   else
   {
      Serial.println("setsensor 22 0");
      cO2LevelHigh = false;
      if(cO2WindowClosed == false) {
         closeWindow();
         cO2WindowClosed = true;
      }
   }
}

void openWindow()
{
   return;
}

void closeWindow()
{
   return;
}

void openDoor()
{
   return;
}

void closeDoor()
{
   return;
}
