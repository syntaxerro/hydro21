# Sterowanie przez websockety

### 1. Inicjalizacja połączenia

> Ekran stoi pod adresem http://127.0.0.1:8075

Na początku należy zestawić połączenie websocket ze sterownikiem pod adresem
```javascript
location.hostname.replace(':8075', ':3181')
// 127.0.0.1:3181
```
Zaraz po zestawieniu połączenia należy **wysłać** do serwera taką wiadomość:
```json
{
  "controller": "register_client"
}
```

Po wysłaniu tej wiadomości serwer zwrotnie wyśle aktualne stany zaworów oraz pompy. Będzie też automatycznie wysyłał te wiadomości jeśli stany będą zmieniane.

### 2. Komunikacja od serwera do klienta [SERVER -> CLIENT]
##### Wiadomość zawierająca aktualny stan zaworów
```json 
{
    "ch1": false,
    "ch2": false,
    "ch3": true,
    "ch4": false,
    "main": false,
    "controller": "current_valves_states"
}
```
Mamy tutaj cztery zawory, które wyłącza i włącza użytkownik (od `ch1` do `ch4`) oraz zawór `main`, który jest sterowany automatycznie. Ich stan powinien być **wyraźnie** widoczny na ekranie.

##### Wiadomość zawierająca aktualny stan wirnika pompy
```json 
{
    "speed": 7.2,
    "controller": "current_pump_state"
}
```
Mamy tutaj prędkość wirnika pompy jako float. Może być ona od 0 do 10. Jeśli jest 0 to znaczy, że pompa nie pracuje.

##### Dodatkowo serwer może wysłać jeszcze taką wiadomość:
```json 
{
    "controller": "pump_state_changing"
}
```
Należy od momentu jej otrzymania **zablokować możliwość zmiany prędkości wirnika** poprzez suwak. W momencie kiedy przyjdzie wiadomość z aktualnym stanem wirnika pompy - zmianę prędkości można powrotnie **odblokować.**

Dodatkowo ten stan może być sygnalizowany "miganiem" pompy lub inną podobną animacją.


### 3. Komunikacja od klienta do serwera [CLIENT -> SERVER]
Klient może do serwera wysyłać komendy do kontrolowania sterownika. Ma on wpływ na stan zaworów od `ch1` do `ch4` oraz na prędkość wirnika pompy.

##### Aby zmienić stan zaworu należy wysłać taką wiadomość:
```json 
{
    "controller": "set_valve",
    "valve": "ch3",
    "state" true
}
```
Powyższa przykładowa wiadomość otwiera zawór na kanale trzecim.

##### Aby zmienić prędkość wirnika pompy należy wysłać taką wiadomość:
```json 
{
    "controller": "set_pump",
    "speed": 4.5
}
```
Powyższa przykładowa wiadomość zmienia prędkość wirnika pompy na 4.5