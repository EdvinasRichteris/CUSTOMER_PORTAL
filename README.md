<a name="br1"></a> 

INFORMATIKOS FAKULTETAS

**T120B165 Saityno taikomųjų programų projektavimas**

**Projekto „Customer Portal“ ataskaita**

Studentas:

Edvinas Richteris, IFF-0/5

Dėstytojas:

Tomas Blažauskas

KAUNAS 2017



<a name="br2"></a> 

Turinys

[1.](#br3)[ ](#br3)[Sprendžiamo](#br3)[ ](#br3)[uždavinio](#br3)[ ](#br3)[aprašymas](#br3)[.......................................................................................................3](#br3)

[1.1.](#br3)[ ](#br3)[Sistemos](#br3)[ ](#br3)[paskirtis..........................................................................................................................3](#br3)

[1.2.](#br3)[ ](#br3)[Funkciniai](#br3)[ ](#br3)[reikalavimai.................................................................................................................3](#br3)

[2.](#br4)[ ](#br4)[Sistemos](#br4)[ ](#br4)[architektūra](#br4)[............................................................................................................................4](#br4)

[3.](#br5)[ ](#br5)[API](#br5)[ ](#br5)[specifikacija...................................................................................................................................5](#br5)

[3.1.](#br5)[ ](#br5)[Krovinių](#br5)[ ](#br5)[API](#br5)[ ](#br5)[metodai](#br5)[...................................................................................................................5](#br5)

[3.2.](#br7)[ ](#br7)[Sąskaitų](#br7)[ ](#br7)[API](#br7)[ ](#br7)[metodai](#br7)[....................................................................................................................7](#br7)

[3.3.](#br10)[ ](#br10)[Komentarų](#br10)[ ](#br10)[API](#br10)[ ](#br10)[metodai](#br10)[ ](#br10)[.............................................................................................................10](#br10)

[4.](#br13)[ ](#br13)[Sistemos](#br13)[ ](#br13)[naudotojo](#br13)[ ](#br13)[sąsaja](#br13)[...................................................................................................................13](#br13)



<a name="br3"></a> 

1\. Sprendžiamo uždavinio aprašymas

1\.1. Sistemos paskirtis

Projekto tikslas – sukurti klientų portalą, kuriame prisijungę klientai galėtų matyti savo

vežamus, bei jau pristatytus krovinius, laukiamas apmokėjimo ir jau apmokėtas sąskaitas, taip

pat galėtų palikti komentarus po sąskaitomis su kurių sumomis nesutinka. Sistema turi būti

saugi, lengvai naudojama bei sklandžiai veikianti.

Veikimo principas – pačią kuriamą platformą sudaro dvi dalys: internetinė aplikacija, kuria

naudosis klientai ir administratoriai, bei aplikacijų programavimo sąsaja (angl. trump. API).

Svečias (įmonės klientas, kuris dar nėra prisiregistravęs) galės registruotis prie internetinės

aplikacijos, tačiau negalės naudotis sistema, kol to nepatvirtins administratorius.

Administratoriui patvirtinus registraciją, klientas galės stebėti savo vežamus bei pristatytus

krovinius, prisegti dokumentus, kurti naujus krovinius, parašyti komentarus po jais jeigu

nesutiks su esama informacija sistemoje. Taip pat galės matyti apmokėtas bei neapmokėtas

sąskaitas bei pranešimus, jei laiku nesumokės sąskaitų. Administratorius galės matyti visų

klientų kompanijų krovinius bei sąskaitas, taip pat galės valdyti naudotojus.

1\.2. Funkciniai reikalavimai

**Neregistruotas sistemos naudotojas galės**:

1\. Peržiūrėti platformos reprezentacinį puslapį;

2\. Prisiregistruoti prie internetinės aplikacijos;

**Registruotas sistemos naudotojas galės**:

1\. Prisijungti prie platformos.

2\. Peržiūrėti savo krovinius.

3\. Sukurti krovinį.

4\. Siųsti pranešimą apie trūkstamą dokumentą (neįkeltas iš įmonės pusės)

5\. Palikti komentarus po kroviniais.

6\. Peržiūrėti savo apmokėtas bei neapmokėtas sąskaitas.



<a name="br4"></a> 

**Administratorius galės**:

1\. Patvirtinti naudotojo registraciją.

2\. Šalinti naudotojus.

3\. Redaguoti krovinius bei sąskaitas.

4\. Siųsti pranešimą klientui dėl vėluojamos apmokėti sąskaitos.

2\. Sistemos architektūra

Sistemos sudedamosios dalys:

• Kliento pusė (ang. Front-End) – naudojant Vue.js;

• Serverio pusė (angl. Back-End) – naudojant PHP Laravel. Duomenų bazė – MySQL.

Internetinė aplikacija yra pasiekiama per HTTPS protokolą. Šios sistemos veikimui (pvz.,

duomenų manipuliavimui su duomenų baze) yra reikalingas CustomerPortal API, kuris

pasiekiamas per aplikacijų programavimo sąsają. Pats CustomerPortal API vykdo duomenų

mainus su duomenų baze - tam naudojama ORM sąsaja.



<a name="br5"></a> 

3\. API specifikacija

Tam, kad būtų išlaikomas nuoseklumas, sistemos API metodai bus aprašomi lentelėmis (žr. 1

lentelę). Jose pateikiama svarbiausia bei būtina informacija sėkmingam užklausos vykdymui. Taip pat

pateikiama informacija apie galimas klaidas, kurios gali iškilti apdorojant tam tikrą API užklausą. Verta

paminėti, kad kelias iki metodo yra nurodomas tik parašant URL galutinę dalį, nes domenas, akivaizdu,

gali skirtis.

3\.1. Krovinių API metodai

*1 lentelė. Loads Get (GET) specifikacija*

API metodas

Paskirtis

Loads Get (GET)

Gražina visų krovinių sąrašą.

Kelias iki metodo (angl. route) /api/loads

Užklausos struktūra

Užklausos „Header“ dalis

Atsakymo struktūra

\-

Authorization: Bearer {token}

[

{

"load\_number": ...,

"load\_status": "...",

"load\_mode": "...",

"customer\_quote\_total": "...",

"customer\_invoice\_total": "...",

"total\_weight": "..."

},

{

"load\_number": ...,

...

},

{

...

},

...

]

Atsakymo kodas

200 (OK)

Galimi klaidų kodai

401 – Invalid token. (Neteisingas token).

401 – Token not provided. (Token null)



<a name="br6"></a> 

*2 lentelė. Load Get (GET) specifikacija*

API metodas

Paskirtis

Load Get (GET)

Gražina pasirinkto krovinio informaciją.

Kelias iki metodo (angl. route) /load/[load\_id]

Užklausos struktūra

Užklausos „Header“ dalis

Atsakymo struktūra

\-

Authorization: Bearer {token}

{

"message": "Load retrieved successfully",

"load": {

"load\_number": …,

"load\_status": "…",

"load\_mode": "…",

"customer\_quote\_total": "…",

"customer\_invoice\_total": "…",

"total\_weight": "…"

}

}

Atsakymo kodas

200 (OK)

Galimi klaidų kodai

401 – Invalid token. (Neteisingas token).

401 – Token not provided. (Token null)



<a name="br7"></a> 

3\.2. Sąskaitų API metodai

*3 lentelė. Invoices All (GET) specifikacija*

API metodas

Paskirtis

Invoices All (GET)

Gauti krovinio visų sąskaitų sąrašą.

Kelias iki metodo (angl. route) /get/load/[load\_id]/invoices

Užklausos struktūra

Užklausos „Header“ dalis

Atsakymo struktūra

\-

Authorization: Bearer {token}

{

"message": "Invoices retrieved successfully",

"invoices": [

{

"invoice\_number": …,

"load\_number": …,

"invoice\_status": "…",

"invoice\_date": "…",

"invoice\_due\_date": "…",

"billing\_contact": "…",

"freight\_charges": "…",

"fuel\_surcharge": "…",

"load\_relation": {

"load\_number": …,

"load\_status": "…",

"load\_mode": "…",

"customer\_quote\_total": "…",

"customer\_invoice\_total": "…",

"total\_weight": "…"

}

},

{

"invoice\_number": …,

…

},

{

…

}

]

}

Atsakymo kodas

200 (OK)

Galimi klaidų kodai

401 – Invalid token. (Neteisingas token).

401 – Token not provided. (Token null)



<a name="br8"></a> 

*4 lentelė. Invoice Get (GET) specifikacija*

API metodas

Paskirtis

Invoice Get (GET)

Gauti sąskaitos informaciją.

Kelias iki metodo (angl. route) /get/load/[load\_id]/invoice/[invoice\_number]

Užklausos struktūra

Užklausos „Header“ dalis

Atsakymo struktūra

\-

Authorization: Bearer {token}

{

"message": "Invoice retrieved successfully",

"invoice": {

"invoice\_number": …,

"load\_number": …,

"invoice\_status": "…",

"invoice\_date": "…",

"invoice\_due\_date": "…",

"billing\_contact": "…",

"freight\_charges": "…",

"fuel\_surcharge": "…",

"load\_relation": {

"load\_number": …,

"load\_status": "…",

"load\_mode": "…",

"customer\_quote\_total": "…",

"customer\_invoice\_total": "…",

"total\_weight": "…"

}

}

}

Atsakymo kodas

200 (OK)

Galimi klaidų kodai

401 – Invalid token. (Neteisingas token).

401 – Token not provided. (Token null)

*5 lentelė. Invoice edit (PUT) specifikacija*

API metodas

Paskirtis

Invoice edit (PUT)

Atnaujinti sąskaitos duomenis.

Kelias iki metodo (angl. route) /edit/load/[load\_id]/invoice/[invoice\_number]

Užklausos struktūra

{

"invoice\_number": …,

"load\_number": …,

"invoice\_status": "…",

"invoice\_date": "…",

"invoice\_due\_date": "…",

"billing\_contact": "…",



<a name="br9"></a> 

"freight\_charges": …,

"fuel\_surcharge": …

}

Užklausos „Header“ dalis

Atsakymo struktūra

Authorization: Bearer {token}

{{

"message": "Invoice updated successfully",

"invoice": {

"invoice\_number": …,

"load\_number": …,

"invoice\_status": "…",

"invoice\_date": "…",

"invoice\_due\_date": "…",

"billing\_contact": "…",

"freight\_charges": …,

"fuel\_surcharge": …

}

}

Atsakymo kodas

200 (OK)

Galimi klaidų kodai

401 – Invalid token. (Neteisingas token).

401 – Token not provided. (Token null)

*6 lentelė. Invoice delete (DEL) specifikacija*

API metodas

Paskirtis

Invoice delete (DEL)

Ištrinti sąskaitą.

Kelias iki metodo (angl. route) /delete/load/[load\_id]/invoice/[invoice\_number]

Užklausos struktūra

Užklausos „Header“ dalis

Atsakymo struktūra

\-

Authorization: Bearer {token}

{

"message": "Invoice deleted sucesfully"

}

Atsakymo kodas

200 (OK)

Galimi klaidų kodai

401 – Invalid token. (Neteisingas token).

401 – Token not provided. (Token null)



<a name="br10"></a> 

3\.3. Komentarų API metodai

*7 lentelė. Comments All (GET) specifikacija*

API metodas

Paskirtis

Comments All (GET)

Gauti komentarų esančių ant tam tikros sąskaitos sąrašą.

Kelias iki metodo (angl. route) /get/load/[loadNumber]/invoice/[invoiceNumber]/comments

Užklausos struktūra

Užklausos „Header“ dalis

Atsakymo struktūra

\-

Authorization: Bearer {token}

{

"message": "Comments retrieved successfully",

"comments": [

{

"id": 41,

"invoice\_number": 13,

"user": 1,

"comment": "test1",

"date": "2023-12-16 15:33:37",

"invoice": {

"invoice\_number": 13,

…

"load\_relation": {

"load\_number": 1003552,

…

}

}

},

{

"id": 49,

"invoice\_number": 13,

"user": 1,

"comment": "test2",

…

}

]

}

Atsakymo kodas

200 (OK)

Galimi klaidų kodai

401 – Invalid token. (Neteisingas token).

401 – Token not provided. (Token null)



<a name="br11"></a> 

*8 lentelė. Comment edit (PUT) specifikacija*

API metodas

Paskirtis

Comment edit (PUT)

Redaguoti komentaro tekstą.

Kelias iki metodo (angl. /edit/load/{loadNumber}/invoice/{invoiceNumber}/comment/{commentId}

route)

Užklausos struktūra

{"comment\_text": "Test edited2"}

Užklausos

„Header“ Authorization: Bearer {token}

dalis

Atsakymo struktūra

{

"message": "Comment updated successfully",

"comment": {

"id": 41,

"invoice\_number": 13,

"user": 1,

"comment": "Test edited2",

"date": "2023-12-16 15:33:37",

"invoice": {

"invoice\_number": 13,

"load\_number": 1003552,

"invoice\_status": "Invoiced",

"invoice\_date": "2023-10-26",

"invoice\_due\_date": "2023-11-10",

"billing\_contact": "Edvinas Test",

"freight\_charges": "500.00",

"fuel\_surcharge": "50.00",

"load\_relation": {

"load\_number": 1003552,

"load\_status": "Delivered",

"load\_mode": "Truckload",

"customer\_quote\_total": "3388.52",

"customer\_invoice\_total": "3388.47",

"total\_weight": "6041.75"

}

}

}

}

Atsakymo kodas

200 (OK)

Galimi klaidų kodai

401 – Invalid token. (Neteisingas token).

401 – Token not provided. (Token null)



<a name="br12"></a> 

*9 lentelė. Comment delete (DEL) specifikacija*

API metodas

Paskirtis

Comment delete (DEL)

Pakeisti užsakymo prekės kategoriją.

Kelias iki metodo (angl. /delete/load/[loadNumber]/invoice/[invoiceNumber]/comment/[commentId]

route)

Užklausos struktūra

\-

Užklausos

„Header“ Authorization: Bearer {token}

dalis

Atsakymo struktūra

{

}

"message": "Comment deleted successfully"

Atsakymo kodas

200 (OK)

Galimi klaidų kodai

401 – Invalid token. (Neteisingas token).

401 – Token not provided. (Token null)



<a name="br13"></a> 

4\. Sistemos naudotojo sąsaja

**Prezentacinis puslapis**



<a name="br14"></a> 

**Prisijungimo forma**

**Krovinių sąrašas**



<a name="br15"></a> 

**Krovinio informacija**



<a name="br16"></a> 

**Krovinio redagavimo modalas**



<a name="br17"></a> 

**Krovinio ištrynimo modalas**



<a name="br18"></a> 

**Sąskaitos informacijos/Sąskaitos komentarų langas**



<a name="br19"></a> 

**Sąskaitos redagavimo modalas**



<a name="br20"></a> 

**Sąskaitos ištrinimo modalas**



<a name="br21"></a> 

**Komentarų redagavimo modalas**

**Komentarų ištrinimo modalas**

