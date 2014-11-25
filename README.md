profitshare-api
===============

Profitshare API pentru Afiliati

Exemplu de utilizare:

Autentificare :
===============
$p = new profitshare("Username API", "Cheie API");

Lista Advertiseri :
===============
$p->getAdvertisersList() ;

Get Products :
===============
$p->getProducts(Advertiser, Pagina); // Exemplu $p->getProducts(35, 1);

Lista Advertiseri :
===============
$p->getAdvertisersCampaigns(Advertiser) ; // Exemplu $p->getAdvertisersCampaigns(35);



