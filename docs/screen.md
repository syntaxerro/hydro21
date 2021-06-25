# Opis funkcjonalności ekranu sterownika

### 1. Ekran sterownika prezentuje:
##### a) aktualny stan czterech zaworów kanałowych

- Jest to grafika przedstawiająca zawór i animuje ona jego otwiernia/zamykanie.
- Obok/nad/pod grafiką byłaby etykieta z napisem jaki jest stan. 
    > Stan TRUE wyświetla się z etykietą **otwarty**. 
    
    > Stan FALSE wyświetla się z etykietą **zamknięty**.
- Obok/nad/pod grafiką jest też mała etykietka (badge?) z literą kanału
    - Kanał 1 to liera A, 
    - kanał 2 to litera B, 
    - kanał 3 to litera C, 
    - kanał czwarty to litera D
- Dodatkowo przy każdym zaworze jest też etykieta z opisem kanału, np. `Lewy przód`
    > Opisy dla każdego z kanałów powinny być konfigurowalny w pliku



##### b) aktualny stan zaworu głównego
- Wyświetla się dokładnie tak samo jak zawory kanałowe z punktu powyżej. 
- **W ten zawór jednak nie da się kliknąć.** Jest on sterowany automatycznie. 
- To znaczy, że trzeba obsłużyć **tylko** wyświetlanie jego aktualnego stanu.

##### c) aktualną prędkość wirnika pompy
- Jest ona prezentowana za pomocą suwaka
- Suwak ma skalę od 0 do 10
- Obok suwaka wyświetla się etykieta z aktualną prędkości wirnika pompy oraz jednostką dla tej wartości, tzn. `litrów/minutę`

##### d) przycisk rozpoczynający / kończący nawadnianie
- Podczas kiedy pompa **nie** pracuje ma kolor zielony i etykietę `Rozpocznij nawadnianie`
- Podczas kiedy pompa **pracuje** ma kolor czerwony i etykietę `Zakończ nawadnianie`
    
    
### 2. Za pomocą ekranu można:
##### a) zmienić stan zaworu kanałowego od 1 do 4 (otwarty/zamknięty)
- Kliknięcie w element prezentujący zawór i wyświetlający jego aktualny stan powoduje zmianę jego aktualnego stanu na przeciwny
- Zmiana stanu zaworu na przeciwny wysyła do sterownika komendę [set_valve](https://github.com/syntaxerro/hydro21/blob/main/docs/websocket.md#aby-zmieni%C4%87-stan-zaworu-nale%C5%BCy-wys%C5%82a%C4%87-tak%C4%85-wiadomo%C5%9B%C4%87)
- Jeśli pompa pracuje (jej aktualna prędkość > 0) to nie można **zamknąć wszystkich czterech zaworów**
    > Wyświetla się w wtedy błąd: `Nie można wyłączyć wszystkich zaworów podczas pracy pompy! Taka konfiguracja grozi uszkodzeniem modułu hydrualicznego.`
    
##### b) zmienić prędkość wirnika pompy
- Zmiana odbywa się za pomocą suwaka prezentującego aktualny stan 
- Podczas kiedy pompa pracuje (jej aktualna prędkość > 0) to zmiana wartości na suwaku **natychmiastowo wysyła** do sterownika komendę [set_pump](https://github.com/syntaxerro/hydro21/blob/main/docs/websocket.md#aby-zmieni%C4%87-pr%C4%99dko%C5%9B%C4%87-wirnika-pompy-nale%C5%BCy-wys%C5%82a%C4%87-tak%C4%85-wiadomo%C5%9B%C4%87)
- Podczas kiedy pompa nie pracuje (jej aktualna prędkośc == 0) zmiana wartości na suwaku **nie wysyła** od razu komendy [set_pump](https://github.com/syntaxerro/hydro21/blob/main/docs/websocket.md#aby-zmieni%C4%87-pr%C4%99dko%C5%9B%C4%87-wirnika-pompy-nale%C5%BCy-wys%C5%82a%C4%87-tak%C4%85-wiadomo%C5%9B%C4%87) do sterownika

##### c) rozpocząć lub zakończyć nawadnianie
- Kliknięcie w przycisk podczas kiedy pompa **nie pracuje** i wartość na suwaku jest > 0 powoduje wysłanie komendy `set_pump` do sterownika
- Kliknięcie w przycisk podczas kiedy pompa **pracuje** powoduje wysłanie do sterownika komendy [set_pump](https://github.com/syntaxerro/hydro21/blob/main/docs/websocket.md#aby-zmieni%C4%87-pr%C4%99dko%C5%9B%C4%87-wirnika-pompy-nale%C5%BCy-wys%C5%82a%C4%87-tak%C4%85-wiadomo%C5%9B%C4%87) z prędkością = 0

