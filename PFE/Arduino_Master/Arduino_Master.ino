#include <SPI.h>
#include <nRF24L01.h>
#include <RF24.h>

RF24 radio(9, 10);       
const byte address[6] = "14863";
const byte address2[6] = "26957";
void setup() 
{
    Serial.begin(9600);
    radio.begin();
    
    radio.openWritingPipe(address2);                  
    radio.openReadingPipe(1, address);
    radio.disableAckPayload();
    
    radio.setPALevel(RF24_PA_MAX);
    radio.stopListening();      
}
void loop()
{  
    bool rslt = false;
    
    radio.stopListening();
    delay(10);
    char data[24];
    char str_temp[6];
    
    sprintf(data, "setcharge %d %d", random(0, 7), random(0, 2));
    radio.write(&data, sizeof(data));             
    delay(1);

    sprintf(data, "setcharge %d %d", random(0, 7), random(0, 2));
    radio.write(&data, sizeof(data));             
    delay(1);

    sprintf(data, "setcharge %d %d", random(0, 7), random(0, 2));
    radio.write(&data, sizeof(data));             
    delay(1);

    sprintf(data, "setcharge %d %d", random(0, 7), random(0, 2));
    radio.write(&data, sizeof(data));             
    delay(1);
    
    radio.startListening();
    delay(13);
    if (radio.available()) 
    {
        char text[24];
        radio.read(&text, sizeof(text));
        Serial.println(text);
    }
}
