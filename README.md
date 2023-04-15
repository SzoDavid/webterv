# BingeVoyage

By **Mammut farka pamut** aka Tandi Áron & Szobonya Dávid

## SQLite3 Konfiguráció

### Telepítés

#### linux/pacman

```bash
sudo pacman -S php-sqlite
```

#### linux/apt

```bash
sudo apt install php-sqlite3
```

#### windows  

[sqlite.org](https://sqlite.org/download.html)

### Beállítások

A xampp mappájában a php-n belül lesz a php.ini konfigurációs file. Végezzük el rajta a 
következő módosításokat (a `post_max_size` és az `upload_max_filesize` alapból 8M, ami 
kevésnek bizonyulhat, `file_uploads`-nak alapból on-nak kéne lennie, `extension=sqlite` 
pedig alapból kommentben van, töröljük előle a `;`-t)!

```
post_max_size = 60M  
file_uploads = On  
upload_max_filesize = 60M  
extension=sqlite
```

Windowson emellet a `libsqlite3.dll`-t tartalmazó php mappát adjuk hozzá a PATH környezeti változóhoz. 

## Tesztelés

Állítólag (bár mi még nem tapasztaltuk) az SQLite adatbázis érzékeny lehet a tömörítésre/mozgatásra, ebben 
az esetben csak töröljük a Resources/database.sqlite fáljt és az újragenerálja magát.

### Admin belépési adatok

Az előre beállított konfigurációs fájl alapján, ha még nincs felhasználó, automatikusan 
generálódik egy admin a következő belépési adatokkal: 

**E-mail:** admin@binge.voyage  
**Jelszó:** Adm1nPass

*(A konfigurációs fáljt nem ér piszkálni!)*
