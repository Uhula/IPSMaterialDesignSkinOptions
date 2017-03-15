### IP Symcon Webfront-Skin, Modul zur Optionseinstellung

**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)
2. [Systemanforderungen](#2-systemanforderungen)
3. [Installation](#3-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)
8. [Changelog](#8-changelog)
9. [Sonstiges](#9-sonstiges)


### 1. Funktionsumfang
Dieses IP Symcon PHP Modul dient dem Einstellen der Optionen des [IPS MaterialDesignSkin](https://github.com/Uhula/IPSMaterialDesignSkin)
für den Webfront.
So lassen sich z.B. Farbthemen für den Skin selbst und für die Akzente wählen.
Weiterhin werden auch Funktionen zur Verfügung gestellt um den Skin via Script verändern zu können.

* [Beispiel grau_blau_schatten](docs/grau_blau_schatten.png?raw=true "grau_blau_schatten")
* [Beispiel grau_blau](docs/grau_blau.png?raw=true "Beispiel grau_blau")
* [Beispiel grau_blau_edit](docs/grau_blau_edit.png?raw=true "Beispiel grau_blau_edit")
* [Beispiel Grau braun_gruen](docs/braun_gruen.png?raw=true "Beispiel braun_gruen")
* [Beispiel blaugrau_segoe_script](docs/blaugrau_segoe_script.png?raw=true "Beispiel blaugrau_segoe_script")
* [Beispiel Grau gruen_orange_schatten](docs/gruen_orange_schatten.png?raw=true "Beispiel gruen_orange_schatten")
* [Beispiel Grau d_grau_bernstein_schatten](docs/d_grau_bernstein_schatten.png?raw=true "Beispiel d_grau_bernstein_schatten")
* [Beispiel Grau d_indigo_blau](docs/d_indigo_blau.png?raw=true "Beispiel d_indigo_blau")


### 2. Systemanforderungen
* IP-Symcon Version 4.0 oder 4.1
* Installierter "Material Design Skin" für den Webfront
  siehe: [IPS MaterialDesignSkin](https://github.com/Uhula/IPSMaterialDesignSkin)


### 3. Installation
Im Objektbaum der IP Symcon Managment Console über die Kern-Instanz "Module" folgende URL hinzufügen:
`git://github.com/Uhula/IPSMaterialDesignSkinOptions.git`


### 4. Einrichten der Instanzen in IP-Symcon

Unter "Instanz hinzufügen" ist das 'MaterialDesignSkinOptions'-Modul unter dem Hersteller 'Webfront' aufgeführt.  

__Konfigurationsseite__:

Name          | Beschreibung
------------- | ---------------------------------
WebfrontID    | ID des Webfronts, der bei Änderungen aktualisiert werden soll
Skin-Thema    | Farbangabe für den Skin (Navigation, Hintergrund, Überschriften)
Accent-Thema  | Farbangabe für die Akzentfarbe (zB für die Bedienfelder)
Schatten      | J/N ob die Container/Karten mit Schatten angezeigt werden sollen
Schriftart    | Zu verwendende Schriftart. Standard ist "Roboto, Arial", aber auch "Segoe Script" sieht nett aus

### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

##### Statusvariablen

Name          | Typ         | Beschreibung
------------- | ----------- | ---------------------------------
WebfrontID    | integer     | ID des Webfronts, der bei Änderungen aktualisiert werden soll
SkinTheme     | MDSO.Theme  | Farbangabe für den Skin (Navigation, Hintergrund, Überschriften)
AccentTheme   | MDSO.Theme  | Farbangabe für die Akzentfarbe (zB für die Bedienfelder)
CardShadow    | MDSO.JaNein | J/N ob die Container/Karten mit Schatten angezeigt werden sollen
Font          | string      | Zu verwendende Schriftart. Standard ist "Roboto, Arial", aber auch "Segoe Script" sieht nett aus
Apply         | MDSO.Apply  | führt zum Anwenden der Änderungen im Webfront

##### Profile:

Name          | Typ         | Beschreibung
------------- | ----------- | ---------------------------------
MDSO.Theme    | integer     | Aufnahme der Farben für Skin/Akzent  
MDSO.JaNein   | boolean     | J/N ob die Container/Karten mit Schatten angezeigt werden sollen
MDSO.Apply    | integer     | 0=Anwenden

### 6. WebFront

Über das WebFront werden die Variablen angezeigt. Eine Änderung der Variablen führt erst durch
"Anwenden" zur Anwendung im Webfront, da hierbei immer ein Reload des Webfronts erfolgt und es
sonst beim Wechsel der Skin-/Akzentfarben "nervig" wäre.

### 7. PHP-Befehlsreferenz

Alle PHP-Befehle erhalten den Prefix MDSO_

##### boolean MDSO_SetSkinTheme( integer $skintheme );  
Setzt das angegebene Skin-Thema und aktualisiert den Webfront.  
Liefert bei Erfolg true, sonst false.  
Beispiel:  
`MDSO_SetSkinTheme( 2 );`

##### boolean MDSO_SetAccentTheme( integer $accenttheme );  
Setzt das angegebene Akzent-Thema und aktualisiert den Webfront.  
Liefert bei Erfolg true, sonst false.  
Beispiel:  
`MDSO_SetAccentTheme( 2 );`

##### boolean MDSO_SetCardShadow( boolean $cardshadow );  
Setzt die Ausgabe der Schatten der Container/Karten auf den übergebenen Wert und aktualisiert den Webfront.  
Liefert bei Erfolg true, sonst false.  
Beispiel:  
`MDSO_SetCardshadow( true );`

##### boolean MDSO_SetFont( string $fontname );  
Angabe der Schriftart, welche verwendet werden soll. Standard ist "Roboto, Arial", aber auch "Segoe Script" sieht nett aus
Liefert bei Erfolg true, sonst false.  
Beispiel:  
`MDSO_SetFont( "Segoe Script, Roboto" );`


### 8. Changelog
Siehe [:link:ChangeLog](./CHANGELOG.md).

### 9. Sonstiges
Verwendung auf eigene Gefahr, der Autor übernimmt weder Gewähr noch Haftung.

:copyright:2016ff Uhula
