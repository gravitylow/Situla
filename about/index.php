<?php
include('../heart.php');
echoHeader(2, "About");
?>
    <div class="container">
      <div class="hero-unit" style="text-align:center;">
        <h2>What does compliance with Situla standards mean?</h2>
        <h5>
        <p>Situla is a set of opt-in standards for code. Developers freely adopt this system, and by doing so claim that their project is in compliance with the list of rules below.</p>
        <br>
        <p>If you see code that claims to comply with Situla rules, but doesn't, be sure to report it so that others know.</p>
        </h5>
        <a class="btn btn-success btn-large btn-inverse" href="checklist.php"><i class="icon-white icon-check"></i> Developer checklist</a>
      </div>

      <h3>By providing compliance with Situla standards, authors claim that they:</h3>
      <div class="row">
        <div class="span9"><i class="icon-ok"></i> Will not force gratification of themselves through their project (why?)</div>
        <div class="span9"><i class="icon-ok"></i> Will use a license that allows others to freely continue their work, in the case the original author leaves for a prolonged period of time.</div>
        <div class="span9"><i class="icon-ok"></i> Provides and maintains sufficient documentation on their project so that one may use it without asking questions.</div>
        <div class="span9"><i class="icon-remove"></i> Won't download things to your system without asking you first, or provide ways for those features to be disabled.</div>
        <div class="span9"><i class="icon-remove"></i> Won't download files from a site other than dev.bukkit.org.</div>
        <div class="span9"><i class="icon-remove"></i> Won't spam you with unnecessary/unwanted information.</div>
      </div>
      <h3>In addition, authors are encouraged to uphold community-based values, including:</h3>
      <ol>
        <li>Providing the maximum support possible for their project</li>
        <li>Making setup/usage simple and easy, as well as providing the simplest use possible</li>
        <li>Providing a multitude of configuration options to provide a tailored user-experience</li>
      </ol>
<?php
    echoFooter();
?>
