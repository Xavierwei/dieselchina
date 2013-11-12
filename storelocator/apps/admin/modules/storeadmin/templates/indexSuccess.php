<div id="wrapper">
   <?php include_partial('header'); ?>
    
    <div id="main">
        <section id="tabs">
            <ul class="sprite-tabs">
                <li class="ui-state-active"><a class="welcome" href="#welcome"><span>WELCOME</span></a></li>
                <li class="disabled"><a class="name-address" href="javascript:void(0);" disabled><span>NAME &amp; ADDRESS</span></a></li>
                <li class="disabled"><a class="opening-times" href="javascript:void(0);"><span>OPENING TIMES</span></a></li>
                <li class="disabled"><a class="store-news" href="javascript:void(0);"><span>STORE NEWS</span></a></li>
                <li><a class="addstore" href="#" onclick="window.location.href='<?php echo url_for('@newstore', true); ?>'; return false;"><span>ADD STORE</span></a></li>
            </ul>
            
            <div id="welcome">
                <article>

                    <h2>Welcome to the Diesel Store Locator Admin</h2>
                    <?php if ($storeadded): ?>
                      <p style="background-color: #99C968; padding-bottom: 0px; margin-bottom: 15px; padding:10px">The store has been added and is waiting for the administrator's approval.</p>
                    <?php endif; ?>
                      
                    <p>Select the store you want to edit:</p>
                    <p style="text-align:right;">Show: <a href="#" onclick="$('#storelist li').show(); return false;">All</a> - <a href="#" onclick="$('#storelist li.open').show(); $('#storelist li.closed').hide(); return false;">Opened</a> - <a href="#" onclick="$('#storelist li.open').hide(); $('#storelist li.closed').show(); return false;">Closed</a> - <a href="#" onclick="$('#storelist li.pending').show(); $('#storelist li.online').hide(); return false;">Pending Approval</a>
                    <ul id="storelist">
                      <?php foreach ($stores as $store): ?>
                        <li class='<?php echo $store->isClosed()?'closed':'open'; ?> <?php echo $store->getOnline()?'online':'pending'; ?>'>
                          <?php if (!$store->getOnline()): ?><img src="/assets/store-admin/img/pending.png" style="margin-right: 10px;"/><?php endif; ?>
                          <strong><?php echo $store->getName() ?></strong> - <a href="<?php echo url_for('storeadmin/editStore?slug='. $store->getSlug()) ?>#name-address">Edit</a></li>
                      <?php endforeach; ?>
                    </ul>

                    <h1>From edit panel you will be able to:</h1>

                    <h2>Name & Address:</h2>
                    <p>Modify your store name and address as well as the shop type (Diesel, Diesel Kid, Diesel & Diesel Kid, etc...). You will also be able to set the exact position of your store on the map.</p>

                    <h2>Opening times:</h2>
                    <p>Specify opening hours, if you wish, that will appear under the address.</p>

                    <h2>Store News:</h2>
                    <p>Inform your clients about special events in your store or in your city, parties, shows, etc..., publish pictures or embed videos</p>

                    <h2>Add Store:</h2>
                    <p>Add a new store not present in your list. If the city is not present in the drop-down menu, you will need to send a message and ask us to add it prior to add the store; to send the request use the <a href="/support">SUPPORT</a> link in the TOP-RIGHT part of the page. Once the store id added, it must be approved by us in order to go live in the website</p>

                    <p>


                    Do not forget to check the <a href="<?php echo url_for('storeadmin/faq'); ?>">FAQ</a> page, through the link in the top-right menu of the page.<br>

                    If you are really in trouble, drop us a line with the <a href="<?php echo url_for('storeadmin/support'); ?>">SUPPORT</a> contact form.

                    Cheers!</p>

                    <p><em>The Diesel Digital Team</em></p>
                  </p>


                </article>
            </div>
            
        </section>
        
    </div>
    
</div>

