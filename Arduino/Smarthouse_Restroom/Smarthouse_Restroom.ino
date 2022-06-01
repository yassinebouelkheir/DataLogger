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
   ScriptName    : Smarthouse_Restroom.ino
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 01/06/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine
*/

#include <SPI.h>
#include <nRF24L01.h>
#include <printf.h>
#include <RF24.h>
#include <RF24_config.h>

RF24 radio(9, 10);       
const byte address[6] = "14863";

unsigned uint timeEplased1;
unsigned uint timeEplased2;

void setup() 
{
    radio.begin();                  
    radio.openWritingPipe(address); 
    radio.setPALevel(RF24_PA_MAX); 
    radio.stopListening();

    pinMode(8, OUTPUT);
    pinMode(9, OUTPUT);
}

void loop()
{  
    char data[24];
    int extractor = 0, movement = 0;

    if(((millis() + 5000) < timeEplased1))
    {
        if(extractor == 1)
        {
            digitalWrite(8, HIGH);
            timeEplased1 = millis();
        }
        else digitalWrite(8, LOW);
    }

    if(((millis() + 5000) < timeEplased2))
    {    
        if(movement == 1)
        {
            digitalWrite(9, HIGH);
            timeEplased2 = millis();
        }
        else digitalWrite(9, LOW);
    }

    sprintf(data, "setsensor 23 %d", extractor);
    radio.write(&data, sizeof(data));     

    sprintf(data, "setsensor 24 %d", movement);
    radio.write(&data, sizeof(data));          
    delay(1);