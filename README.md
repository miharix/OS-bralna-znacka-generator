OS-bralna-znacka-generator
## Generator za avtomatizacijo tiskanja potrdil za bralno značko v OŠ

Ko sem delal kot računalničar(in učitelj) na dveh OŠ sem opazil,
da se knjižničarke mučijo z tiskanjem potrdil bralnih značk, saj so [uradne maske narejene v M$ Wordu](https://www.bralnaznacka.si/sl/motivacijsko-gradivo/) (.doc). 

Pomoč računalničarja pri takem ponavljajočem opravilu je bila seveda potrebna vsako leto znova.

Leta 2015 sem za knjižničarki spisal tale generator, ki za vhodni podatek(.csv) vzame tabelo učencev/k (iz npr eA), ter generira PDF,
 v katerem so podatki učencev na ustreznih mestih za izredno točen in ponovljiv izpis. 

## Postopek dela v grobem:
1. Knjižničarka izdela(izvozi) seznam učencev
2. seznam uvozi v ta generator
3. vloži v tiskalnik paket priznanj
4. klikne natisni in :)

### Podrobno opisan postopek je viden na sami prvi strani generatorja

## Zahteve
*  HTTP Strežnik z HTML in PHP(če se prav spomnim 6.4 ali več)
*  Spletni brskalnik(FireFox) za knjižničarko

## Namestitev:
1.  Nastavi svoj prijubljen spletni strežnik za hostanje nove spletne strani ali ustvari podmapo v obstoječi
2.  Skopiraj celotno vsebino tega git na tisto lokacijo
3.  Daj knjižničarki URL, kjer lahko dostopa do te strani

Uganka **Akronim za cobiss siglo 51371 je:** je zato, da nebi kak boot sprožal generiranje PDF-a in z tem po nepotrebnem obrmenjeval strežnika

PS: Generator vsebuje en za knjižničarko uporaben "Easter egg" *(namig: glej kodo v php)*
