<div class="content">
    <section class="main">
        <div class="page">
            <h2>Glossary</h2>
            <hr/>

            <dl>
                <?php foreach ($tagalog_definitions as $tagalog_definition) { ?>
                    <dt><?php echo $tagalog_definition['Dictionary']['term'] ?></dt>
                    <dd><?php echo $tagalog_definition['Dictionary']['definition'] ?></dd>            
                <?php } ?>
            </dl>            
            <hr>

            <h6>References:</h6>
            <ol>
                <li><a href="http://www.wikipedia.org">http://www.wikipedia.org</a></li>
                <li><a href="http://amsglossary.allenpress.com/glossary/">Glossary of Meteorology</a></li>
                <li>Jack Williams (2009). The AMS Weather Book: The Ultimate Guide to America's Weather.</li>
                <li>Davis Instruments (2009). Vantage Vue Console Manual (Model #6351).</li>
            </ol>
        </div>
    </section><!--MAIN CONTENT-->
</div><!--CONTENT-->