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
   ScriptName    : Arduino_MasterTransmitter.ino
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
const byte address[6] = "26957";

void setup() 
{
    Serial.begin(9600);
    radio.begin();

    radio.openWritingPipe(address);                  
//    radio.disableAckPayload();

    radio.setPALevel(RF24_PA_MAX);
    radio.stopListening(); 

    pinMode(2, OUTPUT);
    pinMode(3, OUTPUT);
    digitalWrite(3, LOW);
    digitalWrite(2, HIGH);
}
void loop()
{   
    String Buff[10];
    int StringCount = 0;
    String data = Serial.readStringUntil('\n');
    
    char datax[24];
        
    if (data.length() > 1) {
        digitalWrite(3, HIGH);
        while (data.length() > 0) 
        {
            int index = data.indexOf(' ');
            if (index == -1) 
            {
                Buff[StringCount++] = data;
                break;
            } 
            else 
            {
                Buff[StringCount++] = data.substring(0, index);
                data = data.substring(index + 1);
            }
        }

        char datax[24];
        sprintf(datax, "setcharge %d %d", int(Buff[1].toInt()), int(Buff[2].toInt()));
        radio.write(&datax, sizeof(datax));
        digitalWrite(3, LOW);
        //Serial.println("OK");
    }
}
