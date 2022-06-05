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
   ScriptName    : Arduino_Smarthouse_Door.ino
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 05/06/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine
*/

#include <string.h>
#include <Keypad.h>
#include <SPI.h>
#include <nRF24L01.h>
#include <printf.h>
#include <RF24.h>
#include <RF24_config.h>

RF24 radio(9, 10);       
const byte address[6] = "57369";

const int ROW_NUM = 4;
const int COLUMN_NUM = 4;
const String accessCodeNumber = "351629";

char keys[ROW_NUM][COLUMN_NUM] = {
   {'1','2','3', 'A'},
   {'4','5','6', 'B'},
   {'7','8','9', 'C'},
   {'*','0','#', 'D'}
};
byte pin_rows[ROW_NUM] = {9, 8, 7, 6}; 
byte pin_column[COLUMN_NUM] = {5, 4, 3, 2}; 
Keypad keypad = Keypad(makeKeymap(keys), pin_rows, pin_column, ROW_NUM, COLUMN_NUM);

int numKeysPressed = 0;
String numEntered = "";

unsigned long timeoutTime = 0;
unsigned long closeDoorTimeout = 0;

void setup()
{
   Serial.begin(9600);
   radio.begin();
           
   radio.openWritingPipe(address);
   radio.disableAckPayload();

   radio.setPALevel(RF24_PA_MAX);
   radio.stopListening();

   pinMode(10, OUTPUT);
   pinMode(11, OUTPUT);
}

void loop()
{
   if((millis()+30000) < timeoutTime)
   {
      numKeysPressed = 0;
      numEntered = "";   
   }

   if((millis()+10000) < closeDoorTimeout)
   {
      sprintf(data, "setsensor 21 0");
      radio.write(&data, sizeof(data)); 
      digitalWrite(10, LOW);
   }

   char key = keypad.getKey();
   if(key)
   {
      numEntered = numEntered + String(key);
      numKeysPressed++;
      timeoutTime = millis();
      tone(11, 2000, 200);
      if(numKeysPressed == 6)
      {
         if(!strcmp(numEntered, accessCodeNumber))
         {
            digitalWrite(10, HIGH);
            sprintf(data, "setsensor 21 1");
            radio.write(&data, sizeof(data));
            closeDoorTimeout = millis();
            tone(11, 5000, 1000);
         }
         else
         {
            tone(11, 500, 1000);
         }
         numKeysPressed = 0;
         numEntered = "";          
      }
   }
}