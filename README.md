# BingeVoyage

By **Mammut farka pamut** aka Tandi Áron & Szobonya Dávid

## Követelmények

* PHP 8.2  
* SQLite
* [XAMPP](https://www.apachefriends.org/) (windowson)

## Konfiguráció

### Linux

Linuxon PhpStorm-mal, vagy IntelliJ IDEA-val (PHP pluginnal) egyszerűen apache konfiguráció nelkül is 
tesztelhető a weboldal.

#### Installációk:

* pacman
  ```bash
  sudo pacman -S php php-cgi php-sqlite
  ```
* apt
  ```bash
  sudo apt install php php-cgi php-sqlite3
  ```

#### Beállítások

Settings > Languages & Frameworks > PHP

PHP language level: `8.2`

CLI Interpreter > ...  
PHP executable: `/usr/bin/php-cgi`

Itt írjuk át a konfigurációs file lokációját is, `sudo vim`/`sudo nano`-val nyissuk meg és végezzük 
el a következő módosításokat (a `post_max_size` és az `upload_max_filesize` alapból 8M, ami kevésnek
bizonyulhat, `file_uploads`-nak alapból on-nak kéne lennie, `extension=sqlite` pedig alapból kommentben
van, töröljük előle a `;`-t)

```
post_max_size = 60M  
file_uploads = On  
upload_max_filesize = 60M  
extension=sqlite
```

Ezután az IDE-ben az `index.php` fájlban jobb felül a böngésző ikonjára kattintva elérhető 
a weboldal.

### Windows

#### Installációk:

Töltsük le az [sqlite](https://sqlite.org/download.html)-ot.

## Tesztelés

Állítólag (bár mi még nem tapasztaltuk) az SQLite adatbázis érzékeny lehet a tömörítésre, ebben 
az esetben csak töröljük a Resources/database.sqlite fáljt és az újragenerálja magát.

Admin belépési adatok:

admin@binge.voyage  
Adm1nPass
