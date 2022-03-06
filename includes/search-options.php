<div class="modal" id="search-modal">
    <div class="search-options">
        <form action="./index.php" method="GET">
            <div>
                <div class="destinations">
                    <button type="button">Destinations</button>
                    <p></p>
                    <input type="hidden" name="destinations">
                </div>

                <div class="dates">
                    <button type="button">Dates</button>
                    <p></p>
                    <input type="hidden" name="dates">
                </div>

                <div class="activities">
                    <button type="button">Activities</button>
                    <p></p>
                    <input type="hidden" name="activities">
                </div>
            </div>

            <div class="show-results">
                <button type="button">Show results</button>
            </div>
        </form>
        <span class="close">&times;</span>
    </div>
</div>

<div class="modal" id="destinations-modal">
    <div class="select-destinations">
        <div class="regions">
            <h2>Regions</h2>
            <div class="search-flex">
                <!-- <div id="current-location">Current location</div> -->
                <div id="hokkaido-tohoku">Hokkaido / Tohoku</div>
                <div id="kanto">Kanto</div>
                <div id="chubu">Chubu</div>
                <div id="kinki">Kinki</div>
                <div id="chugoku">Chugoku</div>
                <div id="shikoku">Shikoku</div>
                <div id="kyushu-okinawa">Kyushu / Okinawa</div>
            </div>
        </div>
        <div class="prefectures">
            <h3>Please select prefecture(s) below.</h3>
            <div class="hokkaido-tohoku search-flex">
                <div>Hokkaido</div>
                <div>Aomori</div>
                <div>Iwate</div>
                <div>Miyagi</div>
                <div>Akita</div>
                <div>Yamagata</div>
                <div>Fukushima</div>
            </div>
            <div class="kanto search-flex">
                <div>Ibaraki</div>
                <div>Tochigi</div>
                <div>Gunma</div>
                <div>Saitama</div>
                <div>Chiba</div>
                <div>Tokyo</div>
                <div>Kanagawa</div>
            </div>
            <div class="chubu search-flex">
                <div>Niigata</div>
                <div>Toyama</div>
                <div>Ishikawa</div>
                <div>Fukui</div>
                <div>Yamanashi</div>
                <div>Nagano</div>
                <div>Gifu</div>
                <div>Shizuoka</div>
                <div>Aichi</div>
            </div>
            <div class="kinki search-flex">
                <div>Mie</div>
                <div>Shiga</div>
                <div>Kyoto</div>
                <div>Osaka</div>
                <div>Hyogo</div>
                <div>Nara</div>
                <div>Wakayama</div>
            </div>
            <div class="chugoku search-flex">
                <div>Tottori</div>
                <div>Shimane</div>
                <div>Okayama</div>
                <div>Hiroshima</div>
                <div>Yamaguchi</div>
            </div>
            <div class="shikoku search-flex">
                <div>Tokushima</div>
                <div>Kagawa</div>
                <div>Ehime</div>
                <div>Kochi</div>
            </div>
            <div class="kyushu-okinawa search-flex">
                <div>Fukuoka</div>
                <div>Saga</div>
                <div>Nagasaki</div>
                <div>Kumamoto</div>
                <div>Oita</div>
                <div>Miyazaki</div>
                <div>Kagoshima</div>
                <div>Okinawa</div>
            </div>
        </div>
        <div class="apply-btn"><button>Apply</button></div>
        <span class="close">&times;</span>
    </div>
</div>

<div class="modal" id="dates-modal">
    <div class="select-dates">
        <div class="dates-options">
            <h2>Dates</h2>
            <div class="search-flex">
                <div id="today">Today</div>
                <div id="tomorrow">Tomorrow</div>
                <div id="this-week">This week</div>
                <div id="next-week">Next week</div>
                <div id="this-month">This month</div>
                <div id="next-month">Next month</div>
                <!-- <div id="select-from-cal">Select from calendar</div> -->
            </div>
        </div>
        <div class="apply-btn"><button>Apply</button></div>
        <span class="close">&times;</span>
    </div>
</div>

<div class="modal" id="activities-modal">
    <div class="select-activities">
        <div class="activities-options">
            <h2>Activities</h2>
            <div class="search-flex">
                <?php echo $activities; ?>
            </div>
        </div>
        <div class="apply-btn"><button>Apply</button></div>
        <span class="close">&times;</span>
    </div>
</div>