=== Wooplatnica ===
Contributors: avram, bloollllldesignlllllstudios, ivijanstefan
Tags: woocommerce, srbija, NBS IPS QR, serbia, uplatnica, opšta uplatnica, common invoice, common invoice slip
Requires at least: 4.5
Tested up to: 5.8.1
Requires PHP: 7.0
License: MIT
Stable tag: trunk
Version: 0.8

WooCommerce payment gateway za generisanje opštih uplatnica i NBS IPS QR kôdova za uplate iz Srbije. 🇷🇸

== Description ==
WooCommerce payment gateway za generisanje opštih uplatnica i NBS IPS QR kôdova za uplate iz Srbije. 🇷🇸

* Po završetku porudžbine korisnik dobija email sa generisanom uplatnicom u PDF formatu.
* Sve uplatnice se čuvaju na serveru (u `wp-content/uplatnice` folderu).
* Opciono, PDF može sadržati i NBS IPS QR kôd za instant plaćanje mobilnim telefonom.


== Installation ==
1. Raspakujte wooplatnica.zip u wp-content/plugins folder
2. Aktivirajte dodatak kroz WP admin panel
3. Folder `wp-content/plugins/wooplatnica/tfpdf/font` (i svi podfolderi) mora imati dozvole za upisivanje
4. Otvorite `WooCommerce > Settings > Payments > Opšta uplatnica` da konfigurišete dodatak
5. To je to!

== Frequently Asked Questions ==
Q: Email ne stiže, zašto?
A: Folder `wp-content/plugins/wooplatnica/tfpdf/font` (i svi podfolderi) mora imati dozvole za upisivanje.

Q: Da li mogu da izmenim cenu prilikom generisanja uplatnice?
A: Da! Dodajte funkciju za filter `wooplatnica_cena` i iz te funkcije vratite izmenjenu cenu.

Q: Uključio sam generisanje NBS IPS QR kôda ali moji kupci nisu svesni toga. Šta da radim?
A: Ažurirajte naslov/opis metoda plaćanja i dodajte informacije o postojanju QR kôda.


== Screenshots ==
1. Odabir načina plaćanja
2. Generisana PDF uplatnica
3. Generisani NBS IPS QR kôd

== Changelog ==

**0.8**
- dodata provera da li je WooCommerce aktiviran pre aktivacije ovog dodatka
- tFPDF ažuriran na poslednju verziju kako bi se izbegla magic quotes gpc greška
- ispravljene sitne greške prijavljene od strane korisnika u v0.7
- minimalna potrebna verzija PHP-a je sada 7.0

**0.7**
- dodato opciono generisanje NBS IPS QR kôda
- dodata opcija da uplatnica sadrži broj telefona platioca
- dodata podrška za PHP 8

**0.6**
- dodata podrška za %products% promenljivu u polju "svrha uplate"

**0.5**
- dodata podrška za drugu liniju adrese kod platioca i primaoca uplate

**0.4**
- ažuriran kod da isprati WooCommerce izmene

**0.3**
- dodati filteri `wooplatnicia_cena` i `wooplatnica_order`, uz pomoc kojih mozete menjati konacnu cenu na uplatnici, odnosno citavu porudzbinu pri generisanju uplatnice
- "uplatilac" promenjeno u "platilac" na samom obrascu

**0.2**
- sitne izmene

**0.1**
- prva verzija
