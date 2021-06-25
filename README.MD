# Sterownik nawodnienia automatycznego

#### Spis treści dokumentacji
1. [Opis funkcjonalności ekranu sterownika](https://github.com/syntaxerro/hydro21/docs/screen.md)
2. [Komunikacja ze sterownikiem poprzez websocket](https://github.com/syntaxerro/hydro21/docs/websocket.md)

#### Wskazówki na początek
1. Pliki prototypu ekranu to:
    * `public/screen.html`
    * `public/screen.js`
    * `public/screen_style.css`
    * `public/utils.css`
2. Cały nowy front ekranu powinien być w folderze `public/screen`
    * Nowy indeks ekranu będzie plikiem `public/screen/index.html`
    * Wszystkie zależności ekranu będą w folderze z ekranem
3. Pod adresem `ws://lacinaleszek411.netes.pl/fake-hydro21` będzie dostępny testowy serwer websocketów
    * Pod adresem `http://lacinaleszek411.netes.pl/hydrolog` będą dostępne logi serwera
        > Strona nie odświeża się sama
    
