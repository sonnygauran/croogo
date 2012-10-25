<div class="content">
    <section class="main">
        <div class="page">
            <h2>Glossary</h2>
            <hr/>
            <h3>Basic Terms</h3>
            <dl>
                <?php foreach ($basic_terms as $basic_term) { ?>
                    <dt><?php echo $basic_term['Dictionary']['term'] ?></dt>
                    <dd><?php echo $basic_term['Dictionary']['definition'] ?></dd>            
                <?php } ?>
            </dl>              

            <hr>

            <h3>Technical Terms</h3>

            <?php foreach ($technical_terms as $technical_term) { ?>
                <dt><?php echo $technical_term['Dictionary']['term'] ?></dt>
                <dd><?php echo $technical_term['Dictionary']['definition'] ?></dd>            
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