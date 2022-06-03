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
   ScriptName    : Arduino_MasterReciever.ino
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 03/06/2022
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

void setup() 
{
    Serial.begin(9600);
    radio.begin();
             
    radio.openReadingPipe(1, address);
    radio.disableAckPayload();

    radio.setPALevel(RF24_PA_MAX);
    radio.startListening(); 

    pinMode(2, OUTPUT);
    pinMode(3, OUTPUT);
    digitalWrite(3, LOW);
    digitalWrite(2, HIGH);
}
void loop()
{   
    digitalWrite(3, HIGH);
    delay(13);
    if (radio.available()) 
    {
        char text[24];
        radio.read(&text, sizeof(text));
        Serial.println(text);
    }
    digitalWrite(3, LOW);
}