<?php
    require 'loader.php';
    global $config;
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Erste Sprache</title>
        <link href="<?php echo $config->urls[0]->common[0]->images; ?>favicon.ico" rel="icon" type="image/x-icon" />
        
	<meta http-equiv="content-type" content="text/html; charset=utf-8" > 
	<meta charset="utf-8">
        <meta http-equiv="content-language" content="de">
        
        <script type="text/javascript" src="<?php echo $config->urls[0]->common[0]->javascript; ?>jQuery.js"></script>
        <script type="text/javascript" src="<?php echo $config->urls[0]->common[0]->javascript; ?>jsViews.js"></script>
        <script type="text/javascript" src="<?php echo $config->urls[0]->common[0]->javascript; ?>BaseTemplating.js"></script>
        
        <script type="text/javascript" src="<?php echo $config->urls[0]->common[0]->javascript; ?>Application.js"></script>
        <script>
            App = new Application();
            App.GetUrlForJsonApi = function () { return '<?php echo $config->urls[0]->common[0]->jsonapi; ?>'; };
            App.GetCallForSaveLanguageData = function () { return 'SaveLanguageData.php'; };
        </script>
        <script type="text/javascript" src="<?php echo $config->urls[0]->common[0]->javascript; ?>LanguageData.js"></script>
        
        <?php if ($config->mode == "production") { ?>
            <link href='http://fonts.googleapis.com/css?family=Cantarell' rel='stylesheet' type='text/css'>
            <link href='http://fonts.googleapis.com/css?family=Gentium+Basic:400italic' rel='stylesheet' type='text/css'>
        <?php } ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $config->urls[0]->common[0]->css; ?>Sprache.css" />
        
        <script id="quote-template" type="text/x-jsrender">
            <div class="progress">
                <div class="count">{^{:Count}} von 25</div>
                <div class="bar">
                    <div class="filled" data-link="css-width{:Count * 4 + '%'}"></div>
                </div>
            </div>
            {^{if Count == 25}}
                <div id="quote">
                    Vielen Dank für's mitmachen!<br>
                    Sobald möglich, stehen hier Ergebnisse.
                </div>
            {{else}}
                <div id="quote" style="height: 100px;">
                    {^{if LanguageData}}
                        „So {^{getValue:LanguageData.Data[0]}} wie {^{getValue:LanguageData.Data[1]}}.“
                    {{else}}
                        <small>Es lädt...</small>
                    {{/if}}
                </div>
                <div class="rating">
                    <div class="item link" onclick="$.observable($.view(this).data.LanguageData).setProperty('Rating', -2); SaveLanguageData();">
                        --
                    </div><div class="item link" onclick="$.observable($.view(this).data.LanguageData).setProperty('Rating', -1); SaveLanguageData();">
                        -
                    </div><div class="item link" onclick="$.observable($.view(this).data.LanguageData).setProperty('Rating', 0); SaveLanguageData();">
                        0
                    </div><div class="item link" onclick="$.observable($.view(this).data.LanguageData).setProperty('Rating', 1); SaveLanguageData();">
                        +
                    </div><div class="item link" onclick="$.observable($.view(this).data.LanguageData).setProperty('Rating', 2); SaveLanguageData();">
                        ++
                    </div>
                </div>
            {{/if}}
        </script>       
        <script>     
            function LoadLanguageData() {
                $.get(App.GetUrlForJsonApi() + 'GetLanguageData.php?Scope=1&End=2', {}, function (data) {
                    Data.DoneLoading = false;
                    
                    if (Data.LanguageData) {
                        Data.LanguageData.ObservablePopulate(data);
                    }
                    else {
                        var languageData = new LanguageData();
                        languageData.Populate(data);
                        $.observable(Data).setProperty("LanguageData", languageData);
                    }
                    
                    Data.DoneLoading = true;
                });
            };   
            function NextLanguageData() {
                $.observable(Data).setProperty("Count", Data.Count + 1); 
                localStorage.setItem('count', Data.Count);
                LoadLanguageData();
            };
            function SaveLanguageData() {
                Data.LanguageData.Send(NextLanguageData);
            };
                
            $(function () {
                Template = $.templates("#quote-template");
                Data = { 
                    LanguageData : null,
                    Count : localStorage['count'] ? parseInt(localStorage['count']) : 0,
                    DoneLoading : false
                };
                Template.link('#target', Data);
                LoadLanguageData(); 
            });
        </script>
    </head>
    <body lang="de">  
        <a href="../Zweite" style="float: right;">ZWEITE SPRACHE →</a><br><br>
        <a href="./Statistik">STATISTIK ANSEHEN</a>
        <h1>erste sprache</h1>
        <br>
        <div id="target"></div>
        <br><br><br><br><br><br>
        DATEN BESTIMMUNGEN<br>
        es werden nur die antworten gespeichert. darüber hinaus werden keine daten gespeichert. die daten werden anonym gespeichert.
        auf deinem computer <i>(js: localStorage)</i> wird die anzahl der beantworteten fragen gespeichert. dies kann nicht gelöscht werden.
        <br>
        <br>
        IMPRESSUM<br> clemens m. schöll // wolfgang-dachstein-straße 3 // 77654 offenburg // schoell@mrssheep.com
        <br>
        <br>
        <a onclick="$('#legal').slideDown(); $(this).fadeOut();">haftungsausschluss anzeigen</a>
        <small style="color: #888; display: none;" id="legal">
            <b>Haftungsausschluss</b>
            <br>
            <i>Haftung für Inhalte</i><br>
            Die Inhalte unserer Seiten wurden mit größter Sorgfalt erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität der Inhalte können wir jedoch keine Gewähr übernehmen. Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.
            <br>
            <i>Haftung für Links</i><br>
            Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.
            <br>
            <i>Urheberrecht</i><br>
            Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet. Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.
            <br>
            <i>Datenschutz</i><br>
            Die Nutzung unserer Webseite ist in der Regel ohne Angabe personenbezogener Daten möglich. Soweit auf unseren Seiten personenbezogene Daten (beispielsweise Name, Anschrift oder eMail-Adressen) erhoben werden, erfolgt dies, soweit möglich, stets auf freiwilliger Basis. Diese Daten werden ohne Ihre ausdrückliche Zustimmung nicht an Dritte weitergegeben. Wir weisen darauf hin, dass die Datenübertragung im Internet (z.B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich. Der Nutzung von im Rahmen der Impressumspflicht veröffentlichten Kontaktdaten durch Dritte zur Übersendung von nicht ausdrücklich angeforderter Werbung und Informationsmaterialien wird hiermit ausdrücklich widersprochen. Die Betreiber der Seiten behalten sich ausdrücklich rechtliche Schritte im Falle der unverlangten Zusendung von Werbeinformationen, etwa durch Spam-Mails, vor.
            <br>
            <i>Datenschutzerklärung für die Nutzung von Facebook-Plugins (Like-Button)</i><br>
            Auf unseren Seiten sind Plugins des sozialen Netzwerks Facebook, 1601 South California Avenue, Palo Alto, CA 94304, USA integriert. Die Facebook-Plugins erkennen Sie an dem Facebook-Logo oder dem "Like-Button" ("Gefällt mir") auf unserer Seite. Eine Übersicht über die Facebook-Plugins finden Sie hier: http://developers.facebook.com/docs/plugins/. Wenn Sie unsere Seiten besuchen, wird über das Plugin eine direkte Verbindung zwischen Ihrem Browser und dem Facebook-Server hergestellt. Facebook erhält dadurch die Information, dass Sie mit Ihrer IP-Adresse unsere Seite besucht haben. Wenn Sie den Facebook "Like-Button" anklicken während Sie in Ihrem Facebook-Account eingeloggt sind, können Sie die Inhalte unserer Seiten auf Ihrem Facebook-Profil verlinken. Dadurch kann Facebook den Besuch unserer Seiten Ihrem Benutzerkonto zuordnen. Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch Facebook erhalten. Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von facebook unter http://de-de.facebook.com/policy.php
            Wenn Sie nicht wünschen, dass Facebook den Besuch unserer Seiten Ihrem Facebook- Nutzerkonto zuordnen kann, loggen Sie sich bitte aus Ihrem Facebook-Benutzerkonto aus. 
        </small> 
    </body>
</html>