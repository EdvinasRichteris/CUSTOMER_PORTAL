![](./r32azng2.png)

> INFORMATIKOS FAKULTETAS
>
> **T120B165** **Saityno** **taikomųjų** **programų** **projektavimas**
>
> **Projekto** **„Customer** **Portal"** **ataskaita**
>
> Studentas:
>
> Dėstytojas:

Edvinas Richteris, IFF-0/5

> Tomas Blažauskas
>
> KAUNAS 2017

Turinys

1\. Sprendžiamo uždavinio
aprašymas\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\....3

> 1.1. Sistemos
> paskirtis\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\.....3
>
> 1.2. Funkciniai
> reikalavimai\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\.....3

2\. Sistemos
architektūra\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\....4

3\. API
specifikacija\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\.....5

> 3.1. Krovinių API
> metodai\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\....5
>
> 3.2. Sąskaitų API
> metodai\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\.....7
>
> 3.3. Komentarų API
> metodai\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\....10

4\. Sistemos naudotojo
sąsaja\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\...\....13

1\. Sprendžiamo uždavinio aprašymas

> 1.1.Sistemos paskirtis
>
> Projekto tikslas -- sukurti klientų portalą, kuriame prisijungę
> klientai galėtų matyti savo vežamus, bei jau pristatytus krovinius,
> laukiamas apmokėjimo ir jau apmokėtas sąskaitas, taip pat galėtų
> palikti komentarus po sąskaitomis su kurių sumomis nesutinka. Sistema
> turi būti saugi, lengvai naudojama bei sklandžiai veikianti.
>
> Veikimo principas -- pačią kuriamą platformą sudaro dvi dalys:
> internetinė aplikacija, kuria naudosis klientai ir administratoriai,
> bei aplikacijų programavimo sąsaja (angl. trump. API).
>
> Svečias (įmonės klientas, kuris dar nėra prisiregistravęs) galės
> registruotis prie internetinės aplikacijos, tačiau negalės naudotis
> sistema, kol to nepatvirtins administratorius. Administratoriui
> patvirtinus registraciją, klientas galės stebėti savo vežamus bei
> pristatytus krovinius, prisegti dokumentus, kurti naujus krovinius,
> parašyti komentarus po jais jeigu nesutiks su esama informacija
> sistemoje. Taip pat galės matyti apmokėtas bei neapmokėtas sąskaitas
> bei pranešimus, jei laiku nesumokės sąskaitų. Administratorius galės
> matyti visų klientų kompanijų krovinius bei sąskaitas, taip pat galės
> valdyti naudotojus.
>
> 1.2. Funkciniai reikalavimai

**Neregistruotas** **sistemos** **naudotojas** **galės**:

> 1\. Peržiūrėti platformos reprezentacinį puslapį; 2. Prisiregistruoti
> prie internetinės aplikacijos;

**Registruotas** **sistemos** **naudotojas** **galės**: 1. Prisijungti
prie platformos.

> 2\. Peržiūrėti savo krovinius. 3. Sukurti krovinį.
>
> 4\. Siųsti pranešimą apie trūkstamą dokumentą (neįkeltas iš įmonės
> pusės) 5. Palikti komentarus po kroviniais.
>
> 6\. Peržiūrėti savo apmokėtas bei neapmokėtas sąskaitas.

**Administratorius** **galės**:

> 1\. Patvirtinti naudotojo registraciją. 2. Šalinti naudotojus.
>
> 3\. Redaguoti krovinius bei sąskaitas.
>
> 4\. Siųsti pranešimą klientui dėl vėluojamos apmokėti sąskaitos.

2\. Sistemos architektūra

> Sistemos sudedamosios dalys:
>
> • Kliento pusė (ang. Front-End) -- naudojant Vue.js;
>
> • Serverio pusė (angl. Back-End) -- naudojant PHP Laravel. Duomenų
> bazė -- MySQL.
>
> Internetinė aplikacija yra pasiekiama per HTTPS protokolą. Šios
> sistemos veikimui (pvz.,
>
> ![](./nf41ew0n.png)duomenų manipuliavimui su duomenų baze)
> yra reikalingas CustomerPortal API, kuris pasiekiamas per aplikacijų
> programavimo sąsają. Pats CustomerPortal API vykdo duomenų mainus su
> duomenų baze - tam naudojama ORM sąsaja.

3\. API specifikacija

Tam, kad būtų išlaikomas nuoseklumas, sistemos API metodai bus aprašomi
lentelėmis (žr. 1 lentelę). Jose pateikiama svarbiausia bei būtina
informacija sėkmingam užklausos vykdymui. Taip pat pateikiama
informacija apie galimas klaidas, kurios gali iškilti apdorojant tam
tikrą API užklausą. Verta paminėti, kad kelias iki metodo yra nurodomas
tik parašant URL galutinę dalį, nes domenas, akivaizdu, gali skirtis.

> 3.1.Krovinių API metodai
>
> _1_ _lentelė._ _Loads_ _Get_ _(GET)_ _specifikacija_

> _2_ _lentelė._ _Load_ _Get_ _(GET)_ _specifikacija_

> 3.2.Sąskaitų API metodai
>
> _3_ _lentelė._ _Invoices_ _All_ _(GET)_ _specifikacija_

> _4_ _lentelė._ _Invoice_ _Get_ _(GET)_ _specifikacija_

> _5_ _lentelė._ _Invoice_ _edit_ _(PUT)_ _specifikacija_

> _6_ _lentelė._ _Invoice_ _delete_ _(DEL)_ _specifikacija_

> 3.3.Komentarų API metodai
>
> _7_ _lentelė._ _Comments_ _All_ _(GET)_ _specifikacija_

> _8_ _lentelė._ _Comment_ _edit_ _(PUT)_ _specifikacija_

> _9_ _lentelė._ _Comment_ _delete_ _(DEL)_ _specifikacija_

4\. Sistemos naudotojo sąsaja

> ![](./kcc0nigz.png)

![](./efi3baaa.png)

> **Prisijungimo** **forma**
>
> ![]**Krovinių** **sąrašas**
>
> ![](./txj5wegu.png)**Krovinio** **informacija**
>
> ![]**Krovinio** **redagavimo** **modalas**
>
> ![](./anf1womz.png)**Krovinio** **ištrynimo** **modalas**
>
> ![](./tynswtgk.png)**Sąskaitos** **informacijos/Sąskaitos** > **komentarų** **langas**
>
> ![](./i1adacmy.png)**Sąskaitos** **redagavimo** **modalas**
>
> ![](./m23j441p.png)**Sąskaitos** **ištrinimo** **modalas**

![](./zf25lm30.png)

> **Komentarų** **redagavimo** **modalas**
>
> ![](./emcokr2e.png)**Komentarų** > **ištrinimo** **modalas**
