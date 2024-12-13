{{-- For Navbar --}}
<section class="topheader">
    <div class="container">
        <div class="row">
            <div class="col-md-2 col-lg-3 top_left">
                <p class="date_time">
                    <span id="DATE_IN_NEPALI">
                        @php
                            use Nilambar\NepaliDate\NepaliDate;

                            // Get current date
                            $currentDate = now();

                            // Convert the current date to Nepali date
                            $obj = new NepaliDate;
                            $nepaliDate = $obj->convertAdToBs($currentDate->year, $currentDate->month, $currentDate->day);
                            $info = $obj->getDetails($nepaliDate['year'], $nepaliDate['month'], $nepaliDate['day'], 'bs', 'np');

                            $nepaliMonths = [
                                'बैशाख', 'जेठ', 'असार', 'श्रावण', 'भाद्र', 'आश्विन',
                                'कार्तिक', 'मंसिर', 'पौष', 'माघ', 'फाल्गुन', 'चैत्र'
                            ];

                            $nepaliDays = [
                                'आइतबार', 'सोमबार', 'मंगलबार', 'बुधबार', 'बिहीबार', 'शुक्रबार', 'शनिबार'
                            ];

                            // Function to convert English numerals to Nepali numerals
                            function convertToNepaliNumeralsForDate($number) {
                                $nepaliNumerals = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
                                return preg_replace_callback('/[0-9]/', function($match) use ($nepaliNumerals) {
                                    return $nepaliNumerals[$match[0]];
                                }, $number);
                            }

                            $nepaliYear = convertToNepaliNumeralsForDate($nepaliDate['year']);
                            $nepaliMonth = $nepaliMonths[$nepaliDate['month'] - 1];
                            $nepaliDay = convertToNepaliNumeralsForDate($nepaliDate['day']);
                            $nepaliDayOfWeek = $nepaliDays[$currentDate->dayOfWeek];

                            $dateDetail = "$nepaliYear $nepaliMonth $nepaliDay गते $nepaliDayOfWeek";

                            echo $dateDetail;
                        @endphp
                    </span>
                </p>
            </div>

            <div class="col-md-5 col-lg-6 top_mid">
                <a href="{{ route('index') }}">
                    <img src="{{ asset('uploads/sitesetting/' .$sitesetting->main_logo) }}" alt="">
                </a>
            </div>

            <div class="col-md-5 col-lg-2 top_right">
                <span class="social_icons">
                    <a href="{{ $sitesetting->facebook }}" target="_blank">
                        <i class="fab fa-facebook-square"></i>
                    </a>
                    <a href="{{ $sitesetting->linkedin }}" target="_blank">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="{{ $sitesetting->twitter }}" target="_blank">
                        <i class="fab fa-twitter-square"></i>
                    </a>
                </span>
            </div>
        </div>
    </div>
</section>
