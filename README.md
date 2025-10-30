# Babarrun kudeaketaren web-a

Repositorio honetan garatutako web aplikazioaren beharrezko fitxategi guztiak aurki daitezke.

## Taldekideak
- Larrain Gonzalez
- Surya Ortega
- Gaizka Divasson
- Erlantz Loriz
- Asier Barrio

## Docker hedaketa proiektuaren garapenerako
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
Gure kasuan, docker-compose.yml artxiboan zehaztuta daukagu datubasea bakarrik inportatzea, erabiltzen dugun init izeneko karpeta erabiliz, baina gerta liteke inportatu beharra, horretarako PDF artxiboa begiratu.
http://localhost:81 -ra nabigatzailetik sartzen bagara, web sistemaren "Home" orrialdera eramango gaitu.  

**REPOSITORIOAN DAGOEN PDF ARTXIBOAN JARRAIPENA AURKITZEN DA.**

Web sistemaren saioa ixteko hurrengo komandoa exekutatu behar dugu beste terminal batean:
```sh
sudo docker-compose down
```
## Makefile erabiliz
Gure web sistemaren repositorioan Makefile artxibo bat dugu, proiektua modu errazagoan kudetzen laguntzen diguna.

### Komandoak

#### make all / make
Komando honek lehenengo gure proiektuaren irudia sortzen du, web izenekoa.
Ondoren "container"-ak abiarazten ditu, http://localhost:81 -ean.

#### make stop
Komando honek "container"-ak gelditzeko balio du.

#### make fclean
Hirugarrenak proiektuarekiko zerikusia duten fitxategiak borratzen ditu, alegia datubasea hasierara bueltatzen du eta docker-ekiko zerikusia duten sareak eta cache-ak ezabatzeaz arduratzen da.

#### make rebuild
Proiektua birkonpilatzen du, gero abiarazteko.

#### make re
Hiru komando bata bestearen atzean exekutatzen ditu.
Lehenengo stop, "container"-ak gelditzeko.
Ondoren flcean, artxiboak hasierara bueltatzeko eta borratzeko
Azkenik all, web irudia sortu berriro eta abiarazten du.



