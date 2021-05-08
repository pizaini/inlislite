<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use inlislite\gii\behaviors\TerminalBehavior;
use \common\models\base\Catalogs as BaseCatalogs;

/**
 * This is the model class for table "catalogs".
 */
class Catalogs extends BaseCatalogs
{

	public $MaxBibId;
	public $MaxControlNumber;
	public $JumlahEdisiSerial;
	public $Eksemplar;
	public $KontenDigital;
	public $Publishment;
	public $PunyaKontenDigital;
	

	//Tag variable
	public $t001;public $t002;public $t003;public $t004;public $t005;public $t006;public $t007;public $t008;public $t009;public $t010;public $t011;public $t012;public $t013;public $t014;public $t015;public $t016;public $t017;public $t018;public $t019;public $t020;public $t021;public $t022;public $t023;public $t024;public $t025;public $t026;public $t027;public $t028;public $t029;public $t030;public $t031;public $t032;public $t033;public $t034;public $t035;public $t036;public $t037;public $t038;public $t039;public $t040;public $t041;public $t042;public $t043;public $t044;public $t045;public $t046;public $t047;public $t048;public $t049;public $t050;public $t051;public $t052;public $t053;public $t054;public $t055;public $t056;public $t057;public $t058;public $t059;public $t060;public $t061;public $t062;public $t063;public $t064;public $t065;public $t066;public $t067;public $t068;public $t069;public $t070;public $t071;public $t072;public $t073;public $t074;public $t075;public $t076;public $t077;public $t078;public $t079;public $t080;public $t081;public $t082;public $t083;public $t084;public $t085;public $t086;public $t087;public $t088;public $t089;public $t090;public $t091;public $t092;public $t093;public $t094;public $t095;public $t096;public $t097;public $t098;public $t099;public $t100;public $t101;public $t102;public $t103;public $t104;public $t105;public $t106;public $t107;public $t108;public $t109;public $t110;public $t111;public $t112;public $t113;public $t114;public $t115;public $t116;public $t117;public $t118;public $t119;public $t120;public $t121;public $t122;public $t123;public $t124;public $t125;public $t126;public $t127;public $t128;public $t129;public $t130;public $t131;public $t132;public $t133;public $t134;public $t135;public $t136;public $t137;public $t138;public $t139;public $t140;public $t141;public $t142;public $t143;public $t144;public $t145;public $t146;public $t147;public $t148;public $t149;public $t150;public $t151;public $t152;public $t153;public $t154;public $t155;public $t156;public $t157;public $t158;public $t159;public $t160;public $t161;public $t162;public $t163;public $t164;public $t165;public $t166;public $t167;public $t168;public $t169;public $t170;public $t171;public $t172;public $t173;public $t174;public $t175;public $t176;public $t177;public $t178;public $t179;public $t180;public $t181;public $t182;public $t183;public $t184;public $t185;public $t186;public $t187;public $t188;public $t189;public $t190;public $t191;public $t192;public $t193;public $t194;public $t195;public $t196;public $t197;public $t198;public $t199;public $t200;public $t201;public $t202;public $t203;public $t204;public $t205;public $t206;public $t207;public $t208;public $t209;public $t210;public $t211;public $t212;public $t213;public $t214;public $t215;public $t216;public $t217;public $t218;public $t219;public $t220;public $t221;public $t222;public $t223;public $t224;public $t225;public $t226;public $t227;public $t228;public $t229;public $t230;public $t231;public $t232;public $t233;public $t234;public $t235;public $t236;public $t237;public $t238;public $t239;public $t240;public $t241;public $t242;public $t243;public $t244;public $t245;public $t246;public $t247;public $t248;public $t249;public $t250;public $t251;public $t252;public $t253;public $t254;public $t255;public $t256;public $t257;public $t258;public $t259;public $t260;public $t261;public $t262;public $t263;public $t264;public $t265;public $t266;public $t267;public $t268;public $t269;public $t270;public $t271;public $t272;public $t273;public $t274;public $t275;public $t276;public $t277;public $t278;public $t279;public $t280;public $t281;public $t282;public $t283;public $t284;public $t285;public $t286;public $t287;public $t288;public $t289;public $t290;public $t291;public $t292;public $t293;public $t294;public $t295;public $t296;public $t297;public $t298;public $t299;public $t300;public $t301;public $t302;public $t303;public $t304;public $t305;public $t306;public $t307;public $t308;public $t309;public $t310;public $t311;public $t312;public $t313;public $t314;public $t315;public $t316;public $t317;public $t318;public $t319;public $t320;public $t321;public $t322;public $t323;public $t324;public $t325;public $t326;public $t327;public $t328;public $t329;public $t330;public $t331;public $t332;public $t333;public $t334;public $t335;public $t336;public $t337;public $t338;public $t339;public $t340;public $t341;public $t342;public $t343;public $t344;public $t345;public $t346;public $t347;public $t348;public $t349;public $t350;public $t351;public $t352;public $t353;public $t354;public $t355;public $t356;public $t357;public $t358;public $t359;public $t360;public $t361;public $t362;public $t363;public $t364;public $t365;public $t366;public $t367;public $t368;public $t369;public $t370;public $t371;public $t372;public $t373;public $t374;public $t375;public $t376;public $t377;public $t378;public $t379;public $t380;public $t381;public $t382;public $t383;public $t384;public $t385;public $t386;public $t387;public $t388;public $t389;public $t390;public $t391;public $t392;public $t393;public $t394;public $t395;public $t396;public $t397;public $t398;public $t399;public $t400;public $t401;public $t402;public $t403;public $t404;public $t405;public $t406;public $t407;public $t408;public $t409;public $t410;public $t411;public $t412;public $t413;public $t414;public $t415;public $t416;public $t417;public $t418;public $t419;public $t420;public $t421;public $t422;public $t423;public $t424;public $t425;public $t426;public $t427;public $t428;public $t429;public $t430;public $t431;public $t432;public $t433;public $t434;public $t435;public $t436;public $t437;public $t438;public $t439;public $t440;public $t441;public $t442;public $t443;public $t444;public $t445;public $t446;public $t447;public $t448;public $t449;public $t450;public $t451;public $t452;public $t453;public $t454;public $t455;public $t456;public $t457;public $t458;public $t459;public $t460;public $t461;public $t462;public $t463;public $t464;public $t465;public $t466;public $t467;public $t468;public $t469;public $t470;public $t471;public $t472;public $t473;public $t474;public $t475;public $t476;public $t477;public $t478;public $t479;public $t480;public $t481;public $t482;public $t483;public $t484;public $t485;public $t486;public $t487;public $t488;public $t489;public $t490;public $t491;public $t492;public $t493;public $t494;public $t495;public $t496;public $t497;public $t498;public $t499;public $t500;public $t501;public $t502;public $t503;public $t504;public $t505;public $t506;public $t507;public $t508;public $t509;public $t510;public $t511;public $t512;public $t513;public $t514;public $t515;public $t516;public $t517;public $t518;public $t519;public $t520;public $t521;public $t522;public $t523;public $t524;public $t525;public $t526;public $t527;public $t528;public $t529;public $t530;public $t531;public $t532;public $t533;public $t534;public $t535;public $t536;public $t537;public $t538;public $t539;public $t540;public $t541;public $t542;public $t543;public $t544;public $t545;public $t546;public $t547;public $t548;public $t549;public $t550;public $t551;public $t552;public $t553;public $t554;public $t555;public $t556;public $t557;public $t558;public $t559;public $t560;public $t561;public $t562;public $t563;public $t564;public $t565;public $t566;public $t567;public $t568;public $t569;public $t570;public $t571;public $t572;public $t573;public $t574;public $t575;public $t576;public $t577;public $t578;public $t579;public $t580;public $t581;public $t582;public $t583;public $t584;public $t585;public $t586;public $t587;public $t588;public $t589;public $t590;public $t591;public $t592;public $t593;public $t594;public $t595;public $t596;public $t597;public $t598;public $t599;public $t600;public $t601;public $t602;public $t603;public $t604;public $t605;public $t606;public $t607;public $t608;public $t609;public $t610;public $t611;public $t612;public $t613;public $t614;public $t615;public $t616;public $t617;public $t618;public $t619;public $t620;public $t621;public $t622;public $t623;public $t624;public $t625;public $t626;public $t627;public $t628;public $t629;public $t630;public $t631;public $t632;public $t633;public $t634;public $t635;public $t636;public $t637;public $t638;public $t639;public $t640;public $t641;public $t642;public $t643;public $t644;public $t645;public $t646;public $t647;public $t648;public $t649;public $t650;public $t651;public $t652;public $t653;public $t654;public $t655;public $t656;public $t657;public $t658;public $t659;public $t660;public $t661;public $t662;public $t663;public $t664;public $t665;public $t666;public $t667;public $t668;public $t669;public $t670;public $t671;public $t672;public $t673;public $t674;public $t675;public $t676;public $t677;public $t678;public $t679;public $t680;public $t681;public $t682;public $t683;public $t684;public $t685;public $t686;public $t687;public $t688;public $t689;public $t690;public $t691;public $t692;public $t693;public $t694;public $t695;public $t696;public $t697;public $t698;public $t699;public $t700;public $t701;public $t702;public $t703;public $t704;public $t705;public $t706;public $t707;public $t708;public $t709;public $t710;public $t711;public $t712;public $t713;public $t714;public $t715;public $t716;public $t717;public $t718;public $t719;public $t720;public $t721;public $t722;public $t723;public $t724;public $t725;public $t726;public $t727;public $t728;public $t729;public $t730;public $t731;public $t732;public $t733;public $t734;public $t735;public $t736;public $t737;public $t738;public $t739;public $t740;public $t741;public $t742;public $t743;public $t744;public $t745;public $t746;public $t747;public $t748;public $t749;public $t750;public $t751;public $t752;public $t753;public $t754;public $t755;public $t756;public $t757;public $t758;public $t759;public $t760;public $t761;public $t762;public $t763;public $t764;public $t765;public $t766;public $t767;public $t768;public $t769;public $t770;public $t771;public $t772;public $t773;public $t774;public $t775;public $t776;public $t777;public $t778;public $t779;public $t780;public $t781;public $t782;public $t783;public $t784;public $t785;public $t786;public $t787;public $t788;public $t789;public $t790;public $t791;public $t792;public $t793;public $t794;public $t795;public $t796;public $t797;public $t798;public $t799;public $t800;public $t801;public $t802;public $t803;public $t804;public $t805;public $t806;public $t807;public $t808;public $t809;public $t810;public $t811;public $t812;public $t813;public $t814;public $t815;public $t816;public $t817;public $t818;public $t819;public $t820;public $t821;public $t822;public $t823;public $t824;public $t825;public $t826;public $t827;public $t828;public $t829;public $t830;public $t831;public $t832;public $t833;public $t834;public $t835;public $t836;public $t837;public $t838;public $t839;public $t840;public $t841;public $t842;public $t843;public $t844;public $t845;public $t846;public $t847;public $t848;public $t849;public $t850;public $t851;public $t852;public $t853;public $t854;public $t855;public $t856;public $t857;public $t858;public $t859;public $t860;public $t861;public $t862;public $t863;public $t864;public $t865;public $t866;public $t867;public $t868;public $t869;public $t870;public $t871;public $t872;public $t873;public $t874;public $t875;public $t876;public $t877;public $t878;public $t879;public $t880;public $t881;public $t882;public $t883;public $t884;public $t885;public $t886;public $t887;public $t888;public $t889;public $t890;public $t891;public $t892;public $t893;public $t894;public $t895;public $t896;public $t897;public $t898;public $t899;public $t900;public $t901;public $t902;public $t903;public $t904;public $t905;public $t906;public $t907;public $t908;public $t909;public $t910;public $t911;public $t912;public $t913;public $t914;public $t915;public $t916;public $t917;public $t918;public $t919;public $t920;public $t921;public $t922;public $t923;public $t924;public $t925;public $t926;public $t927;public $t928;public $t929;public $t930;public $t931;public $t932;public $t933;public $t934;public $t935;public $t936;public $t937;public $t938;public $t939;public $t940;public $t941;public $t942;public $t943;public $t944;public $t945;public $t946;public $t947;public $t948;public $t949;public $t950;public $t951;public $t952;public $t953;public $t954;public $t955;public $t956;public $t957;public $t958;public $t959;public $t960;public $t961;public $t962;public $t963;public $t964;public $t965;public $t966;public $t967;public $t968;public $t969;public $t970;public $t971;public $t972;public $t973;public $t974;public $t975;public $t976;public $t977;public $t978;public $t979;public $t980;public $t981;public $t982;public $t983;public $t984;public $t985;public $t986;public $t987;public $t988;public $t989;public $t990;public $t991;public $t992;public $t993;public $t994;public $t995;public $t996;public $t997;public $t998;public $t999;

	public $labels = array();
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
    	$defaultLabel =[
            'ID' => Yii::t('app', 'ID'),
            'ControlNumber' => Yii::t('app', 'Control Number'),
            'BIBID' => Yii::t('app', 'Bibid'),
            'Title' => Yii::t('app', 'Title'),
            'Author' => Yii::t('app', 'Author'),
            'Edition' => Yii::t('app', 'Edition'),
            'Publisher' => Yii::t('app', 'Publisher'),
            'PublishLocation' => Yii::t('app', 'Publish Location'),
            'PublishYear' => Yii::t('app', 'Publish Year'),
            'Publikasi' => Yii::t('app', 'Publikasi'),
            'Subject' => Yii::t('app', 'Subject'),
            'PhysicalDescription' => Yii::t('app', 'Physical Description'),
            'ISBN' => Yii::t('app', 'Isbn'),
            'CallNumber' => Yii::t('app', 'Call Number'),
            'Note' => Yii::t('app', 'Note'),
            'Languages' => Yii::t('app', 'Languages'),
            'DeweyNo' => Yii::t('app', 'Dewey No'),
            'ApproveDateOPAC' => Yii::t('app', 'Approve Date Opac'),
            'IsOPAC' => Yii::t('app', 'Is Opac'),
            'IsBNI' => Yii::t('app', 'Is Bni'),
            'IsKIN' => Yii::t('app', 'Is Kin'),
            'CoverURL' => Yii::t('app', 'Cover Url'),
            'Branch_id' => Yii::t('app', 'Branch ID'),
            'Worksheet_id' => Yii::t('app', 'Worksheet ID'),
            'CreateBy' => Yii::t('app', 'Create By'),
            'CreateDate' => Yii::t('app', 'Create Date'),
            'CreateTerminal' => Yii::t('app', 'Create Terminal'),
            'UpdateBy' => Yii::t('app', 'Update By'),
            'UpdateDate' => Yii::t('app', 'Update Date'),
            'UpdateTerminal' => Yii::t('app', 'Update Terminal'),
            'MARC_LOC' => Yii::t('app', 'Marc  Loc'),
            'PRESERVASI_ID' => Yii::t('app', 'Preservasi  ID'),
            'QUARANTINEDBY' => Yii::t('app', 'Quarantinedby'),
            'QUARANTINEDDATE' => Yii::t('app', 'Quarantineddate'),
            'QUARANTINEDTERMINAL' => Yii::t('app', 'Quarantinedterminal'),
            'Member_id' => Yii::t('app', 'Member ID'),
            'KIILastUploadDate' => Yii::t('app', 'Kiilast Upload Date'),
            'Publishment'=>  Yii::t('app', 'Publishment'),
            'IsRDA'=>  Yii::t('app', 'IsRDA'),
        ];
    	for ($i=1; $i < 1000 ; $i++) { 
			$tag =  str_pad($i, 3, '0', STR_PAD_LEFT);
			$labels[] = array('t'.$tag => Yii::t('app', $tag));
		}
        return array_merge($defaultLabel,$labels);
    }

    public function loadByBIBID($BIBID)
    {
        $model =  BaseCatalogs::find()->where(['BIBID'=>$BIBID])->all();
        return $model;
    }

    /**
     * @inheritdoc
     * @return type array
     */ 
    public function behaviors()
    {
        return [
        //\nhkey\arh\ActiveRecordHistoryBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'CreateDate',
                'updatedAtAttribute' => 'UpdateDate',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'CreateBy',
                'updatedByAttribute' => 'UpdateBy',
            ],
            [
                'class' => TerminalBehavior::className(),
                'createdTerminalAttribute' => 'CreateTerminal',
                'updatedTerminalAttribute' => 'UpdateTerminal',
                'value' => \Yii::$app->request->userIP,
            ],
        ];
    }
}
