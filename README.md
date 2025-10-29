# ISSKS Proiektua

Repositorio honetan garatutako web aplikazioaren beharrezko fitxategi guztiak aurki daitezke.

## Taldekideak
- Larrain Gonzalez
- Surya Ortega
- Gaizka Divasson
- Erlantz Loriz
- Asier Barrio

## [Docker](https://www.docker.com/) hedaketa proiektuaren garapenerako
### Docker instalatu
Lehenengo terminalean hurrengo komandoa exekutatuko dugu:
```sh
sudo apt-get update
```
Komando honek sisteman eskuragarri dauden paketeen lista lokala eguneratzen ditu.

Docker instalatzeko hurrengo komandoa exekutatuko dugu terminalean:
```sh
sudo apt install docker.io
```
docker-compose behar izango dugunez, hurrengo komandoa exekutatuko dugu terminalean:
```sh
sudo apt install docker-compose
```

### Web-era sartzeko konfiguraketa
#### Azalpen zehatzak repositorioan dagoen PDF artxiboan daude 
Repositorioko Dockerfile direktorioan kokatuta, terminalean hurrengoa exekutatzen dugu:
```sh
sudo docker build -t="web" .
```
Honek web izeneko irudi bat sortuko du, eraikitako web sistemara sartzen ahalbidetuko diguena.

Azkenik, web sistema probatzeko hurrego komandoa exekutatzen dugu terminalean:
```sh
sudo docker-compose up
```
http://localhost:81 -ra nabigatzailetik sartzen bagara, web sistemaren "Home" orrialdera eramango gaitu.  

**REPOSITORIOAN DAGOEN PDF ARTXIBOAN JARRAIPENA AURKITZEN DA.**

Web sistemaren saioa ixteko hurrengo komandoa exekutatu behar dugu beste terminal batean:
```sh
sudo docker-compose down
```

