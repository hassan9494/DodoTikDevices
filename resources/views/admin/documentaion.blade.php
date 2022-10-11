@extends('layouts.admin')

@section('styles')
    <style>
        .picture-container {
            position: relative;
            cursor: pointer;
            text-align: center;
        }

        .picture {
            width: 800px;
            height: 400px;
            background-color: #999999;
            border: 4px solid #CCCCCC;
            color: #FFFFFF;
            /* border-radius: 50%; */
            margin: 5px auto;
            overflow: hidden;
            transition: all 0.2s;
            -webkit-transition: all 0.2s;
        }

        .picture:hover {
            border-color: #2ca8ff;
        }

        .picture input[type="file"] {
            cursor: pointer;
            display: block;
            height: 100%;
            left: 0;
            opacity: 0 !important;
            position: absolute;
            top: 0;
            width: 100%;
        }

        .picture-src {
            width: 100%;
            height: 100%;
        }
    </style>

@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <section class="box-fancy section-fullwidth text-dark p-b-0">
        <div class="row" style="text-align: -webkit-center;">
            <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
                <div class="card card-custom mb-4">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.Documentation')}}</span>
                        </h3>

                        <div class="card-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test" role="tablist">

                                {{--                            <li class="nav-item nav-item">--}}
                                {{--                                <a href="{{route('admin.devices.location', [$device->id])}}" role="tab"--}}
                                {{--                                   data-rb-event-key="Location"--}}
                                {{--                                   aria-selected="true"--}}
                                {{--                                   class="nav-link py-2 px-4  nav-link active ">{{__('message.Edit Location')}}</a></li>--}}
                            </ul>
                        </div>

                    </div>


                    <div class="card-body pt-2" style="position: relative;">
                        <div class="col-md-12 ">


                            <div class="row">
                                <div class="col-md-12" style="margin-top: 15px;margin-bottom: 15px">
                                    <div class=WordSection1>

                                        <p class=MsoListParagraphCxSpFirst dir=RTL style='margin-top:0in;margin-right:
.5in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
text-indent:-.25in;mso-list:l1 level1 lfo1;direction:rtl;unicode-bidi:embed'><![if !supportLists]><span
                                                style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-fareast-font-family:Arial;mso-fareast-theme-font:minor-bidi;mso-hansi-theme-font:
minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:minor-bidi'><span
                                                    style='mso-list:Ignore'>1-<span
                                                        style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span dir=RTL></span><span lang=AR-SY
                                                          style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi;mso-bidi-language:AR-SY'>&#1576;&#1593;&#1583;
&#1578;&#1587;&#1580;&#1610;&#1604; &#1575;&#1604;&#1583;&#1582;&#1608;&#1604;
&#1610;&#1578;&#1605; &#1575;&#1604;&#1575;&#1606;&#1578;&#1602;&#1575;&#1604;
&#1575;&#1604;&#1609; (</span><span dir=LTR style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi;mso-bidi-language:AR-SY'>Device Types </span><span
                                                dir=RTL></span><span lang=AR-SY style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi;mso-bidi-language:AR-SY'><span dir=RTL></span><span
                                                    style='mso-spacerun:yes'> </span>) </span><span dir=LTR style='font-family:
"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;
mso-bidi-font-family:Arial;mso-bidi-theme-font:minor-bidi'><o:p></o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
.5in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
direction:rtl;unicode-bidi:embed'><span dir=LTR style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi;mso-no-proof:yes'><!--[if gte vml 1]><v:shapetype
 id="_x0000_t75" coordsize="21600,21600" o:spt="75" o:preferrelative="t"
 path="m@4@5l@4@11@9@11@9@5xe" filled="f" stroked="f">
 <v:stroke joinstyle="miter"/>
 <v:formulas>
  <v:f eqn="if lineDrawn pixelLineWidth 0"/>
  <v:f eqn="sum @0 1 0"/>
  <v:f eqn="sum 0 0 @1"/>
  <v:f eqn="prod @2 1 2"/>
  <v:f eqn="prod @3 21600 pixelWidth"/>
  <v:f eqn="prod @3 21600 pixelHeight"/>
  <v:f eqn="sum @0 0 1"/>
  <v:f eqn="prod @6 1 2"/>
  <v:f eqn="prod @7 21600 pixelWidth"/>
  <v:f eqn="sum @8 21600 0"/>
  <v:f eqn="prod @7 21600 pixelHeight"/>
  <v:f eqn="sum @10 21600 0"/>
 </v:formulas>
 <v:path o:extrusionok="f" gradientshapeok="t" o:connecttype="rect"/>
 <o:lock v:ext="edit" aspectratio="t"/>
</v:shapetype><v:shape id="Picture_x0020_1" o:spid="_x0000_i1026" type="#_x0000_t75"
 style='width:153.75pt;height:324.75pt;visibility:visible;mso-wrap-style:square'>
 <v:imagedata src="&#1576;&#1593;&#1583;%20&#1578;&#1587;&#1580;&#1610;&#1604;%20&#1575;&#1604;&#1583;&#1582;&#1608;&#1604;%20&#1610;&#1578;&#1605;%20&#1575;&#1604;&#1575;&#1606;&#1578;&#1602;&#1575;&#1604;%20&#1575;&#1604;&#1609;_files/image001.png"
  o:title=""/>
</v:shape><![endif]--><![if !vml]><img width=205 height=433
                                       src="{{ asset('admin/img/doc2.png')}}"
                                       v:shapes="Picture_x0020_1"><![endif]></span><span dir=LTR style='font-family:
"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;
mso-bidi-font-family:Arial;mso-bidi-theme-font:minor-bidi'><o:p></o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
.5in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
direction:rtl;unicode-bidi:embed'><span dir=LTR style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi'><o:p>&nbsp;</o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
.5in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
direction:rtl;unicode-bidi:embed'><span dir=LTR style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi'><o:p>&nbsp;</o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
.5in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
text-indent:-.25in;mso-list:l1 level1 lfo1;direction:rtl;unicode-bidi:embed'><![if !supportLists]><span
                                                style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-fareast-font-family:Arial;mso-fareast-theme-font:minor-bidi;mso-hansi-theme-font:
minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:minor-bidi'><span
                                                    style='mso-list:Ignore'>2-<span
                                                        style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span dir=RTL></span><span lang=AR-SY
                                                          style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi;mso-bidi-language:AR-SY'>&#1610;&#1578;&#1605;
&#1575;&#1604;&#1606;&#1602;&#1585; &#1593;&#1604;&#1609; </span><span dir=LTR
                                                                       style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi;mso-bidi-language:AR-SY'>Add New </span><span dir=LTR
                                                         style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi'><o:p></o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
.5in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
text-indent:-.25in;mso-list:l1 level1 lfo1;direction:rtl;unicode-bidi:embed'><![if !supportLists]><span
                                                style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-fareast-font-family:Arial;mso-fareast-theme-font:minor-bidi;mso-hansi-theme-font:
minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:minor-bidi'><span
                                                    style='mso-list:Ignore'>3-<span
                                                        style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span dir=RTL></span><span lang=AR-SY
                                                          style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi;mso-bidi-language:AR-SY'>&#1610;&#1578;&#1605; &#1605;&#1604;&#1574;
&#1575;&#1587;&#1605; &#1575;&#1604;&#1606;&#1608;&#1593;
&#1608;&#1575;&#1604;&#1576;&#1575;&#1585;&#1575;&#1605;&#1578;&#1585;&#1575;&#1578;
&#1575;&#1604;&#1582;&#1575;&#1589;&#1577; &#1601;&#1610;&#1607; &#1576;&#1575;&#1604;&#1575;&#1590;&#1575;&#1601;&#1577;
&#1575;&#1604;&#1609;
&#1575;&#1604;&#1575;&#1593;&#1583;&#1575;&#1583;&#1575;&#1578; (</span><span
                                                dir=LTR style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi;mso-bidi-language:AR-SY'>Settings</span><span dir=RTL></span><span
                                                lang=AR-SY style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi;mso-bidi-language:AR-SY'><span dir=RTL></span> )
&#1575;&#1584;&#1575; &#1603;&#1575;&#1606; &#1604;&#1607;
&#1575;&#1593;&#1583;&#1575;&#1583;&#1575;&#1578;
&#1608;&#1576;&#1593;&#1583;&#1607;&#1575; &#1610;&#1578;&#1605;
&#1575;&#1604;&#1606;&#1602;&#1585; &#1593;&#1604;&#1609; &#1586;&#1585; </span><span
                                                dir=LTR style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi;mso-bidi-language:AR-SY'>create</span><span dir=LTR
                                                       style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi'><o:p></o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
.5in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
text-indent:-.25in;mso-list:l1 level1 lfo1;direction:rtl;unicode-bidi:embed'><![if !supportLists]><span
                                                style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-fareast-font-family:Arial;mso-fareast-theme-font:minor-bidi;mso-hansi-theme-font:
minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:minor-bidi'><span
                                                    style='mso-list:Ignore'>4-<span
                                                        style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span dir=RTL></span><span lang=AR-SY
                                                          style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi;mso-bidi-language:AR-SY'>&#1578;&#1592;&#1607;&#1585;
&#1589;&#1601;&#1581;&#1577; &#1593;&#1604;&#1609;
&#1575;&#1604;&#1588;&#1603;&#1604; &#1575;&#1604;&#1575;&#1578;&#1610;
&#1601;&#1610;&#1607;&#1575; &#1593;&#1583;&#1583; &#1605;&#1606;
&#1575;&#1604;&#1605;&#1585;&#1576;&#1593;&#1575;&#1578; &#1608;&#1607;&#1610;
&#1603;&#1575;&#1604;&#1578;&#1575;&#1604;&#1610;:</span><span dir=LTR
                                                               style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi'><o:p></o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
1.0in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
text-indent:-.25in;mso-list:l0 level1 lfo2;direction:rtl;unicode-bidi:embed'><![if !supportLists]><span
                                                style='font-family:Symbol;mso-fareast-font-family:Symbol;mso-bidi-font-family:
Symbol'><span style='mso-list:Ignore'>·<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span dir=RTL></span><span dir=LTR
                                                          style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi'>Device Settings </span><span dir=RTL></span><span style='font-family:
"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;
mso-bidi-font-family:Arial;mso-bidi-theme-font:minor-bidi'><span dir=RTL></span><span
                                                    style='mso-spacerun:yes'> </span></span><span lang=AR-SY style='font-family:
"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;
mso-bidi-font-family:Arial;mso-bidi-theme-font:minor-bidi;mso-bidi-language:
AR-SY'>: &#1578;&#1581;&#1608;&#1610; &#1593;&#1604;&#1609;
&#1575;&#1604;&#1575;&#1593;&#1583;&#1575;&#1583;&#1575;&#1578; &#1601;&#1610;
&#1581;&#1575;&#1604; &#1603;&#1575;&#1606; &#1604;&#1604;&#1606;&#1608;&#1593;
&#1575;&#1593;&#1583;&#1575;&#1583;&#1575;&#1578; &#1610;&#1578;&#1605;
&#1605;&#1604;&#1572;&#1607;&#1575; &#1576;&#1575;&#1604;&#1602;&#1610;&#1605;
&#1575;&#1604;&#1575;&#1601;&#1578;&#1585;&#1575;&#1590;&#1610;&#1577;
&#1575;&#1608; &#1575;&#1604;&#1575;&#1608;&#1604;&#1610;&#1577; </span><span
                                                dir=LTR style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi'><o:p></o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
1.0in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
text-indent:-.25in;mso-list:l0 level1 lfo2;direction:rtl;unicode-bidi:embed'><![if !supportLists]><span
                                                style='font-family:Symbol;mso-fareast-font-family:Symbol;mso-bidi-font-family:
Symbol'><span style='mso-list:Ignore'>·<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span dir=RTL></span><span dir=LTR
                                                          style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi;color:#181C32;background:white'>Device Parameters Order</span><span
                                                dir=RTL></span><span lang=AR-SA style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi;color:#181C32;background:white'><span
                                                    dir=RTL></span> : &#1610;&#1581;&#1608;&#1610; &#1593;&#1604;&#1609; &#1578;&#1585;&#1578;&#1610;&#1576;
&#1575;&#1604;&#1576;&#1575;&#1585;&#1575;&#1605;&#1578;&#1585;&#1575;&#1578;
&#1610;&#1578;&#1605; &#1578;&#1581;&#1583;&#1610;&#1583;
&#1575;&#1604;&#1578;&#1585;&#1578;&#1610;&#1576; &#1605;&#1606;&#1607; </span><span
                                                dir=LTR style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi'><o:p></o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
1.0in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
text-indent:-.25in;mso-list:l0 level1 lfo2;direction:rtl;unicode-bidi:embed'><![if !supportLists]><span
                                                dir=RTL></span><span style='font-family:Symbol;mso-fareast-font-family:Symbol;
mso-bidi-font-family:Symbol'><span style='mso-list:Ignore'>·<span
                                                        style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span dir=RTL></span><span dir=RTL></span><span
                                                lang=AR-SA style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi'><span dir=RTL></span></span><span dir=LTR style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi'>Device Parameters Length</span><span
                                                dir=RTL></span><span lang=AR-SY style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi;mso-bidi-language:AR-SY'><span dir=RTL></span>
: &#1610;&#1581;&#1608;&#1610; &#1593;&#1604;&#1609; &#1591;&#1608;&#1604;
&#1603;&#1604; &#1576;&#1575;&#1585;&#1575;&#1605;&#1578;&#1585;
&#1590;&#1605;&#1606; &#1575;&#1604;&#1583;&#1575;&#1578;&#1575; &#1575;&#1604;&#1605;&#1585;&#1587;&#1604;&#1577;
&#1593;&#1606; &#1591;&#1585;&#1610;&#1602; &#1575;&#1604; </span><span
                                                class=SpellE><span dir=LTR style='font-family:"Arial","sans-serif";mso-ascii-theme-font:
minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;
mso-bidi-theme-font:minor-bidi;mso-bidi-language:AR-SY'>api</span></span><span
                                                dir=RTL></span><span lang=AR-SY style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi;mso-bidi-language:AR-SY'><span dir=RTL></span>
&#1603;&#1605;&#1579;&#1575;&#1604; &#1593;&#1604;&#1610;&#1607; &#1601;&#1610;
&#1575;&#1604;&#1606;&#1608;&#1593;
&#1583;&#1608;&#1583;&#1608;&#1604;&#1608;&#1585;&#1575;
&#1575;&#1604;&#1584;&#1610; &#1603;&#1606;&#1575; &#1606;&#1593;&#1605;&#1604;
&#1593;&#1604;&#1610;&#1607; &#1587;&#1575;&#1576;&#1602;&#1575;
&#1603;&#1575;&#1606; &#1591;&#1608;&#1604;
&#1575;&#1604;&#1576;&#1575;&#1585;&#1575;&#1605;&#1578;&#1585;&#1575;&#1578;
&#1575;&#1604;&#1581;&#1585;&#1575;&#1585;&#1577; &#1608;&#1575;&#1604;&#1585;&#1591;&#1608;&#1576;&#1577;
&#1608;&#1575;&#1604;&#1601;&#1608;&#1604;&#1578;&#1610;&#1577; 4
&#1582;&#1575;&#1606;&#1575;&#1578; &#1601;&#1610;&#1605;&#1575;
&#1603;&#1575;&#1606; &#1575;&#1604;&#1594;&#1575;&#1586; &#1576; 8
&#1582;&#1575;&#1606;&#1575;&#1578;</span><span dir=LTR style='font-family:
"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;
mso-bidi-font-family:Arial;mso-bidi-theme-font:minor-bidi'><o:p></o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
1.0in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
text-indent:-.25in;mso-list:l0 level1 lfo2;direction:rtl;unicode-bidi:embed'><![if !supportLists]><span
                                                style='font-family:Symbol;mso-fareast-font-family:Symbol;mso-bidi-font-family:
Symbol'><span style='mso-list:Ignore'>·<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></span></span><![endif]><span dir=RTL></span><span dir=LTR
                                                          style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi;mso-bidi-language:AR-SY'>Device Parameters Rate</span><span dir=RTL></span><span
                                                lang=AR-SY style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi;mso-bidi-language:AR-SY'><span dir=RTL></span> :
&#1610;&#1581;&#1608;&#1610; &#1593;&#1604;&#1609;
&#1575;&#1604;&#1585;&#1602;&#1605; &#1575;&#1604;&#1584;&#1610;
&#1587;&#1608;&#1601; &#1606;&#1602;&#1608;&#1605;
&#1576;&#1578;&#1602;&#1587;&#1610;&#1605; &#1602;&#1610;&#1605;&#1577; &#1602;&#1585;&#1575;&#1569;&#1577;
&#1575;&#1604;&#1576;&#1575;&#1585;&#1575;&#1605;&#1578;&#1585;
&#1593;&#1604;&#1610;&#1607; &#1576;&#1593;&#1583;
&#1575;&#1606;&#1607;&#1575;&#1569; &#1601;&#1603;
&#1575;&#1604;&#1578;&#1588;&#1601;&#1610;&#1585; &#1608;&#1575;&#1604;&#1581;&#1589;&#1608;&#1604;
&#1593;&#1604;&#1609; &#1575;&#1604;&#1602;&#1610;&#1605;&#1577;
&#1575;&#1604;&#1606;&#1607;&#1575;&#1574;&#1610;&#1577;
&#1608;&#1603;&#1605;&#1579;&#1575;&#1604; &#1601;&#1610; &#1606;&#1608;&#1593;
&#1583;&#1608;&#1583;&#1608;&#1604;&#1608;&#1585;&#1575; &#1603;&#1606;&#1575;
&#1606;&#1602;&#1587;&#1605; &#1602;&#1610;&#1605;
&#1576;&#1575;&#1585;&#1575;&#1605;&#1578;&#1585;&#1575;&#1578;
&#1575;&#1604;&#1581;&#1585;&#1575;&#1585;&#1577; &#1608;&#1575;&#1604;&#1585;&#1591;&#1608;&#1576;&#1577;
&#1608;&#1575;&#1604;&#1601;&#1608;&#1604;&#1578;&#1610;&#1577;
&#1593;&#1604;&#1609; 10 &#1575;&#1605;&#1575;
&#1575;&#1604;&#1594;&#1575;&#1586; &#1601;&#1603;&#1606;&#1575;
&#1606;&#1602;&#1587;&#1605;&#1607; &#1593;&#1604;&#1609; 1000
&#1604;&#1604;&#1581;&#1589;&#1608;&#1604; &#1593;&#1604;&#1609;
&#1575;&#1604;&#1602;&#1610;&#1605;
&#1575;&#1604;&#1606;&#1607;&#1575;&#1574;&#1610;&#1577; </span><span dir=LTR
                                                                      style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi'><o:p></o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
1.0in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
direction:rtl;unicode-bidi:embed'><span lang=AR-SY style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi;mso-bidi-language:AR-SY'><o:p>&nbsp;</o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
1.0in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
direction:rtl;unicode-bidi:embed'><span lang=AR-SY style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi;mso-bidi-language:AR-SY'><o:p>&nbsp;</o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
1.0in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
direction:rtl;unicode-bidi:embed'><span lang=AR-SA style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi'><o:p>&nbsp;</o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
1.0in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
direction:rtl;unicode-bidi:embed'><span lang=AR-SA style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi'>&#1576;&#1593;&#1583;
&#1575;&#1604;&#1575;&#1606;&#1578;&#1607;&#1575;&#1569; &#1605;&#1606;
&#1605;&#1604;&#1574; &#1607;&#1584;&#1607;
&#1575;&#1604;&#1581;&#1602;&#1608;&#1604; &#1606;&#1590;&#1594;&#1591;
&#1593;&#1604;&#1609; &#1586;&#1585; </span><span dir=LTR style='font-family:
"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;
mso-bidi-font-family:Arial;mso-bidi-theme-font:minor-bidi'>update</span><span
                                                dir=RTL></span><span lang=AR-SY style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi;mso-bidi-language:AR-SY'><span dir=RTL></span>
&#1608;&#1610;&#1603;&#1608;&#1606; &#1602;&#1583; &#1578;&#1605;
&#1575;&#1604;&#1581;&#1601;&#1592; &#1604;&#1604;&#1606;&#1608;&#1593; <o:p></o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
.5in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
direction:rtl;unicode-bidi:embed'><span lang=AR-SA style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi;mso-no-proof:yes'><o:p>&nbsp;</o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
.5in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
direction:rtl;unicode-bidi:embed'><span lang=AR-SA style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi;mso-no-proof:yes'><o:p>&nbsp;</o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
.5in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
direction:rtl;unicode-bidi:embed'><span dir=LTR style='mso-no-proof:yes'><!--[if gte vml 1]><v:shape
 id="Picture_x0020_3" o:spid="_x0000_i1025" type="#_x0000_t75" style='width:468pt;
 height:237pt;visibility:visible;mso-wrap-style:square'>
 <v:imagedata src="&#1576;&#1593;&#1583;%20&#1578;&#1587;&#1580;&#1610;&#1604;%20&#1575;&#1604;&#1583;&#1582;&#1608;&#1604;%20&#1610;&#1578;&#1605;%20&#1575;&#1604;&#1575;&#1606;&#1578;&#1602;&#1575;&#1604;%20&#1575;&#1604;&#1609;_files/image002.png"
  o:title=""/>
</v:shape><![endif]--><![if !vml]><img width=624 height=316
                                       src="{{ asset('admin/img/doc1.png')}}"
                                       v:shapes="Picture_x0020_3"><![endif]></span><span lang=AR-SA style='font-family:
"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;
mso-bidi-font-family:Arial;mso-bidi-theme-font:minor-bidi'><o:p></o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
.5in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
direction:rtl;unicode-bidi:embed'><span lang=AR-SA style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi'><o:p>&nbsp;</o:p></span></p>

                                        <p class=MsoListParagraphCxSpMiddle dir=RTL style='margin-top:0in;margin-right:
.5in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
direction:rtl;unicode-bidi:embed'><span lang=AR-SA style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi'><o:p>&nbsp;</o:p></span></p>

                                        <p class=MsoListParagraphCxSpLast dir=RTL style='margin-top:0in;margin-right:
.5in;margin-bottom:8.0pt;margin-left:0in;mso-add-space:auto;text-align:right;
direction:rtl;unicode-bidi:embed'><span lang=AR-SA style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi'>&#1576;&#1593;&#1583; &#1575;&#1606;
&#1610;&#1578;&#1605; &#1575;&#1604;&#1581;&#1601;&#1592; &#1610;&#1578;&#1605;
&#1575;&#1590;&#1575;&#1601;&#1577; &#1580;&#1607;&#1575;&#1586;
&#1603;&#1605;&#1575; &#1603;&#1575;&#1606; &#1587;&#1575;&#1576;&#1602;&#1575;
&#1608;&#1610;&#1578;&#1605; &#1575;&#1582;&#1578;&#1610;&#1575;&#1585;
&#1606;&#1608;&#1593;&#1607; &#1605;&#1606; &#1575;&#1604;&#1606;&#1608;&#1593;
&#1575;&#1604;&#1584;&#1610; &#1578;&#1605;
&#1575;&#1590;&#1575;&#1601;&#1578;&#1607; &#1608;&#1610;&#1578;&#1605;
&#1575;&#1604;&#1578;&#1593;&#1575;&#1605;&#1604; &#1605;&#1593;&#1607;
&#1576;&#1606;&#1601;&#1587; &#1575;&#1604;&#1575;&#1604;&#1610;&#1577;
&#1575;&#1604;&#1587;&#1575;&#1576;&#1602;&#1577;
&#1608;&#1585;&#1576;&#1591;&#1607; &#1576;&#1575;&#1604;&#1605;&#1606;&#1589;&#1577;
&#1593;&#1606; &#1591;&#1585;&#1610;&#1602; &#1606;&#1601;&#1587;
&#1575;&#1604;</span><span class=SpellE><span dir=LTR style='font-family:"Arial","sans-serif";
mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:
Arial;mso-bidi-theme-font:minor-bidi'>api</span></span><span dir=LTR
                                                             style='font-family:"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;
mso-hansi-theme-font:minor-bidi;mso-bidi-font-family:Arial;mso-bidi-theme-font:
minor-bidi'> </span><span dir=RTL></span><span lang=AR-SY style='font-family:
"Arial","sans-serif";mso-ascii-theme-font:minor-bidi;mso-hansi-theme-font:minor-bidi;
mso-bidi-font-family:Arial;mso-bidi-theme-font:minor-bidi;mso-bidi-language:
AR-SY'><span dir=RTL></span><span
                                                    style='mso-spacerun:yes'> </span>&#1575;&#1604;&#1587;&#1575;&#1576;&#1602; <o:p></o:p></span>
                                        </p>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
@endpush
