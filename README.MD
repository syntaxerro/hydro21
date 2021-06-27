# Sterownik nawodnienia automatycznego

### Spis treści dokumentacji
1. [Opis funkcjonalności ekranu sterownika](https://github.com/syntaxerro/hydro21/blob/main/docs/screen.md)
2. [Komunikacja ze sterownikiem poprzez websocket](https://github.com/syntaxerro/hydro21/blob/main/docs/websocket.md)
3. [Schemat ekranu sterownika](https://github.com/syntaxerro/hydro21/blob/main/docs/screen_schema.md)

### Wskazówki na początek
1. Pliki prototypu ekranu to:
    * [public/screen.html](https://github.com/syntaxerro/hydro21/blob/main/public/screen.html)
    * [public/screen.js](https://github.com/syntaxerro/hydro21/blob/main/public/screen.js)
    * [public/screen_style.css](https://github.com/syntaxerro/hydro21/blob/main/public/screen_style.css)
    * [public/utils.css](https://github.com/syntaxerro/hydro21/blob/main/public/utils.css)
2. Cały nowy front ekranu powinien być w folderze `public/screen`
    * Nowy indeks ekranu będzie plikiem `public/screen/index.html`
    * Wszystkie zależności ekranu będą w folderze z ekranem
3. Aby uruchomić testowy serwer websocket 
    - Należy mieć zainstalowanego [PHP w wersji 7.3](https://windows.php.net/downloads/releases/php-7.3.28-Win32-VC15-x64.zip)
    - Następnie skopiować plik [config.yml](https://github.com/syntaxerro/hydro21/blob/main/config.yml) jako `config.local.yml`
    - W pliku `config.local.yml` trzeba **zakomentować** serwisy do łączenia z bazą danych
    ```yml
    # Database production
    #  db.config:
    #    factory: ['Doctrine\ORM\Tools\Setup', 'createAnnotationMetadataConfiguration']
    #    arguments: [['/opt/hydro21/src'], '%dev%', null, null, false]
    #
    #  db.em:
    #    factory: ['Doctrine\ORM\EntityManager', 'create']
    #    arguments: ['%database%', '@db.config']
    # 
    #  db.repository.scheduled_task:
    #    factory: ['@db.em', 'getRepository']
    #    arguments: ['App\Entity\ScheduledTask']
    ```
    - W pliku `config.local.yml` trzeba **odkomentować** mocki bazy
    ```yml
    # Database test
      db.em:
        class: App\TCPController\Mock\EntityManager
    
      db.repository.scheduled_task:
        class: App\TCPController\Mock\ScheduledTaskRepository
        arguments: ['@db.em']
    ```
    - Uruchomić przez `php.exe` plik `bin\hydromock.php`
        > Pełna komenda to `php.exe bin\hydromock.php`
        
#### Często występujące błędy
1. Przede wszystkim należy skopiować zawartość pliku [config.yml](https://github.com/syntaxerro/hydro21/blob/main/config.yml) do `config.local.yml` 
2. Potwórzyć kroki z `Wskazówki na początek: PUNKT 3` odnośnie podmiany bazy danych na mocki
> Po aktualizacji kodu z repozytorium wykonanie tego może być konieczne.  
    
