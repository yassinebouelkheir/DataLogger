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

#define DHTTYPE DHT22

RF24 radio(9, 10);       
const byte address1[6] = "63257";
const byte address2[6] = "57369";

DHT dhtext(53, DHTTYPE);
DHT dhtint(52, DHTTYPE);

bool cO2LevelHigh = false;
bool cO2WindowClosed = true;

const int ROW_NUM = 4;
const int COLUMN_NUM = 4;
const String accessCodeNumber = "351629";

char keys[ROW_NUM][COLUMN_NUM] = {
   {'1','2','3', 'A'},
   {'4','5','6', 'B'},
   {'7','8','9', 'C'},
   {'*','0','#', 'D'}
};
byte pin_rows[ROW_NUM] = {29, 28, 27, 26}; 
byte pin_column[COLUMN_NUM] = {25, 24, 23, 22}; 
Keypad keypad = Keypad(makeKeymap(keys), pin_rows, pin_column, ROW_NUM, COLUMN_NUM);

int numKeysPressed = 0;
String numEntered = "";

unsigned long timeoutTime = 0;
unsigned long closeDoorTimeout = 0;

void setup() 
{
   radio.begin();

   radio.openReadingPipe(1, address2);
   radio.openWritingPipe(address1);
//   radio.disableAckPayload();

   radio.setPALevel(RF24_PA_MAX); 
   radio.stopListening(); 

   for(int i = 22; i < 30; i++) pinMode(i, OUTPUT);
   pinMode(2, OUTPUT);
   pinMode(3, OUTPUT);
   digitalWrite(3, HIGH);
   digitalWrite(2, LOW);
   noTone(2);
   pinMode(53, INPUT_PULLUP);
   pinMode(52, INPUT_PULLUP);
   Serial.begin(9600);
}

void loop() 
{
   radio.startListening();
   delay(15);

   if(radio.available())
   {
      char text[32];
      radio.read(&text, sizeof(text));
      Serial.println(text);
   }

   radio.stopListening();
   delay(100);

   if((millis()+30000) < timeoutTime)
   {
      numKeysPressed = 0;
      numEntered = "";   
   }

   if((millis()+10000) < closeDoorTimeout)
   {
      char data[24];
      Serial.println("setsensor 21 0"); 
      closeDoor();
   }

   char key = keypad.getKey();
   if(key)
   {
      numEntered = numEntered + String(key);
      numKeysPressed++;
      timeoutTime = millis();
      //tone(2, 2000, 200);
      if(numKeysPressed == 6)
      {
         if(/*!strcmp(numEntered, accessCodeNumber)*/1)
         {
            char data[24];
            Serial.println("setsensor 21 1");
            openDoor();
            closeDoorTimeout = millis();
            //tone(2, 5000, 1000);
         }
         else
         {
            //tone(2, 500, 1000);
         }
         numKeysPressed = 0;
         numEntered = "";          
      }
   }

   char data[3];
   sprintf(data, "00");
   //float cO2Level = 0.0;
   
   float tempext = dhtext.readTemperature();
   Serial.println("setsensor 14 " + String(tempext));
   float tempint = dhtint.readTemperature();
   Serial.println("setsensor 15 " + String(tempint));
   float humidityint = dhtint.readHumidity();
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
   if(brightness < 400)
   {
      digitalWrite(3, LOW);
      Serial.println("setsensor 20 1");
   }
   else 
   {
      digitalWrite(3, HIGH);   
      Serial.println("setsensor 20 0"); 
   }    

   float cO2Level = 0.0;
   
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
