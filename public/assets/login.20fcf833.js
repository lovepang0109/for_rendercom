import{W as Us,X as zs,bN as Ys,Z as js,bO as qs,k as C,a0 as Gs,p as m,ak as Hs,j as Xs,bP as Zs,bQ as Qs,o as ne,b as ve,w as y,s as we,q as G,bu as Js,U as J,m as X,x as Ks,D as te,P as re,c as $s,bl as er,bc as sr,bv as rr,J as ir}from"./index.84400ea8.js";import{u as ar,U as or}from"./useAppAbility.3e9b8b60.js";import{n as fe}from"./index.c0fab4f2.js";import{b as be}from"./route-block.011d1056.js";import{d as W,V as ge,b as le,c as ie,a as nr,e as tr}from"./VRow.5bc57025.js";import{V as lr}from"./VDialog.e12e96f6.js";import{a as dr,b as ur,V as cr}from"./VTextarea.d0bbf08b.js";const mr=Us({...zs(),...Ys()},"VForm"),pr=js()({name:"VForm",props:mr(),emits:{"update:modelValue":A=>!0,submit:A=>!0},setup(A,O){let{slots:T,emit:b}=O;const p=qs(A),c=C();function o(V){V.preventDefault(),p.reset()}function N(V){const h=V,I=p.validate();h.then=I.then.bind(I),h.catch=I.catch.bind(I),h.finally=I.finally.bind(I),b("submit",h),h.defaultPrevented||I.then(D=>{var v;let{valid:M}=D;M&&((v=c.value)==null||v.submit())}),h.preventDefault()}return Gs(()=>{var V;return m("form",{ref:c,class:["v-form",A.class],style:A.style,novalidate:!0,onReset:o,onSubmit:N},[(V=T.default)==null?void 0:V.call(T,p)])}),Hs(p,c)}}),vr=A=>A==null,wr=A=>Array.isArray(A)&&A.length===0,fr="/assets/panel-sms-logo.93480cd7.png",br="/assets/bg.efbd80d6.jpg",Ee=A=>vr(A)||wr(A)||A===!1?"This field is required":!!String(A).trim().length||"This field is required";var ae={exports:{}};(function(A){A.exports=function(O){var T={};function b(p){if(T[p])return T[p].exports;var c=T[p]={i:p,l:!1,exports:{}};return O[p].call(c.exports,c,c.exports,b),c.l=!0,c.exports}return b.m=O,b.c=T,b.d=function(p,c,o){b.o(p,c)||Object.defineProperty(p,c,{configurable:!1,enumerable:!0,get:o})},b.n=function(p){var c=p&&p.__esModule?function(){return p.default}:function(){return p};return b.d(c,"a",c),c},b.o=function(p,c){return Object.prototype.hasOwnProperty.call(p,c)},b.p="",b(b.s=1)}([function(O,T,b){var p={MOBILE:"mobile",TABLET:"tablet",SMART_TV:"smarttv",CONSOLE:"console",WEARABLE:"wearable",BROWSER:void 0},c={CHROME:"Chrome",FIREFOX:"Firefox",OPERA:"Opera",YANDEX:"Yandex",SAFARI:"Safari",INTERNET_EXPLORER:"Internet Explorer",EDGE:"Edge",CHROMIUM:"Chromium",IE:"IE",MOBILE_SAFARI:"Mobile Safari",EDGE_CHROMIUM:"Edge Chromium"},o={IOS:"iOS",ANDROID:"Android",WINDOWS_PHONE:"Windows Phone",WINDOWS:"Windows",MAC_OS:"Mac OS"},N={isMobile:!1,isTablet:!1,isBrowser:!1,isSmartTV:!1,isConsole:!1,isWearable:!1};O.exports={BROWSER_TYPES:c,DEVICE_TYPES:p,OS_TYPES:o,defaultData:N}},function(O,T,b){var p=b(2),c=b(0),o=c.BROWSER_TYPES,N=c.OS_TYPES,V=c.DEVICE_TYPES,h=b(4),I=h.checkType,D=h.broPayload,M=h.mobilePayload,v=h.wearPayload,x=h.consolePayload,i=h.stvPayload,a=h.getNavigatorInstance,s=h.isIOS13Check,e=new p,r=e.getBrowser(),t=e.getDevice(),d=e.getEngine(),n=e.getOS(),l=e.getUA(),F=o.CHROME,Z=o.CHROMIUM,oe=o.IE,k=o.INTERNET_EXPLORER,R=o.OPERA,U=o.FIREFOX,K=o.SAFARI,_=o.EDGE,Y=o.YANDEX,j=o.MOBILE_SAFARI,f=V.MOBILE,g=V.TABLET,w=V.SMART_TV,S=V.BROWSER,P=V.WEARABLE,H=V.CONSOLE,E=N.ANDROID,q=N.WINDOWS_PHONE,B=N.IOS,$=N.WINDOWS,ee=N.MAC_OS,ye=function(){return t.type===f},Te=function(){return t.type===g},he=function(){switch(t.type){case f:case g:return!0;default:return!1}},de=function(){return n.name===N.WINDOWS&&n.version==="10"?typeof l=="string"&&l.indexOf("Edg/")!==-1:!1},Se=function(){return t.type===w},Pe=function(){return t.type===S},Oe=function(){return t.type===P},Ve=function(){return t.type===H},xe=function(){return n.name===E},Ae=function(){return n.name===$},Ie=function(){return n.name===ee},Ne=function(){return n.name===q},_e=function(){return n.name===B},Me=function(){return r.name===F},Re=function(){return r.name===U},Ce=function(){return r.name===Z},ue=function(){return r.name===_},ke=function(){return r.name===Y},Be=function(){return r.name===K||r.name===j},De=function(){return r.name===j},We=function(){return r.name===R},Le=function(){return r.name===k||r.name===oe},Fe=function(){var L=a(),se=L&&L.userAgent.toLowerCase();return typeof se=="string"?/electron/.test(se):!1},Ue=function(){var L=a();return L&&(/iPad|iPhone|iPod/.test(L.platform)||L.platform==="MacIntel"&&L.maxTouchPoints>1)&&!window.MSStream},Q=function(){return s("iPad")},ze=function(){return s("iPhone")},Ye=function(){return s("iPod")},je=function(){return r.major},qe=function(){return r.version},Ge=function(){return n.version?n.version:"none"},He=function(){return n.name?n.name:"none"},Xe=function(){return r.name},Ze=function(){return t.vendor?t.vendor:"none"},Qe=function(){return t.model?t.model:"none"},Je=function(){return d.name},Ke=function(){return d.version},$e=function(){return l},es=function(){return t.type},ss=Se(),rs=Ve(),is=Oe(),as=De()||Q(),os=Ce(),ns=he()||Q(),ts=ye(),ls=Te()||Q(),ds=Pe(),us=xe(),cs=Ne(),ms=_e()||Q(),ps=Me(),vs=Re(),ws=Be(),fs=We(),bs=Le(),gs=Ge(),Es=He(),ys=je(),Ts=qe(),hs=Xe(),Ss=Ze(),Ps=Qe(),Os=Je(),Vs=Ke(),xs=$e(),As=ue()||de(),Is=ke(),Ns=es(),_s=Ue(),Ms=Q(),Rs=ze(),Cs=Ye(),ks=Fe(),Bs=de(),Ds=ue(),Ws=Ae(),Ls=Ie(),z=I(t.type);function Fs(){var u=z.isBrowser,L=z.isMobile,se=z.isTablet,ce=z.isSmartTV,me=z.isConsole,pe=z.isWearable;if(u)return D(u,r,d,n,l);if(ce)return i(ce,d,n,l);if(me)return x(me,d,n,l);if(L||se)return M(z,t,n,l);if(pe)return v(pe,d,n,l)}O.exports={deviceDetect:Fs,isSmartTV:ss,isConsole:rs,isWearable:is,isMobileSafari:as,isChromium:os,isMobile:ns,isMobileOnly:ts,isTablet:ls,isBrowser:ds,isAndroid:us,isWinPhone:cs,isIOS:ms,isChrome:ps,isFirefox:vs,isSafari:ws,isOpera:fs,isIE:bs,osVersion:gs,osName:Es,fullBrowserVersion:ys,browserVersion:Ts,browserName:hs,mobileVendor:Ss,mobileModel:Ps,engineName:Os,engineVersion:Vs,getUA:xs,isEdge:As,isYandex:Is,deviceType:Ns,isIOS13:_s,isIPad13:Ms,isIPhone13:Rs,isIPod13:Cs,isElectron:ks,isEdgeChromium:Bs,isLegacyEdge:Ds,isWindows:Ws,isMacOs:Ls}},function(O,T,b){var p;/*!
* UAParser.js v0.7.18
* Lightweight JavaScript-based User-Agent string parser
* https://github.com/faisalman/ua-parser-js
*
* Copyright © 2012-2016 Faisal Salman <fyzlman@gmail.com>
* Dual licensed under GPLv2 or MIT
*/(function(c,o){var N="0.7.18",V="",h="?",I="function",D="undefined",M="object",v="string",x="major",i="model",a="name",s="type",e="vendor",r="version",t="architecture",d="console",n="mobile",l="tablet",F="smarttv",Z="wearable",oe="embedded",k={extend:function(f,g){var w={};for(var S in f)g[S]&&g[S].length%2===0?w[S]=g[S].concat(f[S]):w[S]=f[S];return w},has:function(f,g){return typeof f=="string"?g.toLowerCase().indexOf(f.toLowerCase())!==-1:!1},lowerize:function(f){return f.toLowerCase()},major:function(f){return typeof f===v?f.replace(/[^\d\.]/g,"").split(".")[0]:o},trim:function(f){return f.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,"")}},R={rgx:function(f,g){for(var w=0,S,P,H,E,q,B;w<g.length&&!q;){var $=g[w],ee=g[w+1];for(S=P=0;S<$.length&&!q;)if(q=$[S++].exec(f),q)for(H=0;H<ee.length;H++)B=q[++P],E=ee[H],typeof E===M&&E.length>0?E.length==2?typeof E[1]==I?this[E[0]]=E[1].call(this,B):this[E[0]]=E[1]:E.length==3?typeof E[1]===I&&!(E[1].exec&&E[1].test)?this[E[0]]=B?E[1].call(this,B,E[2]):o:this[E[0]]=B?B.replace(E[1],E[2]):o:E.length==4&&(this[E[0]]=B?E[3].call(this,B.replace(E[1],E[2])):o):this[E]=B||o;w+=2}},str:function(f,g){for(var w in g)if(typeof g[w]===M&&g[w].length>0){for(var S=0;S<g[w].length;S++)if(k.has(g[w][S],f))return w===h?o:w}else if(k.has(g[w],f))return w===h?o:w;return f}},U={browser:{oldsafari:{version:{"1.0":"/8",1.2:"/1",1.3:"/3","2.0":"/412","2.0.2":"/416","2.0.3":"/417","2.0.4":"/419","?":"/"}}},device:{amazon:{model:{"Fire Phone":["SD","KF"]}},sprint:{model:{"Evo Shift 4G":"7373KT"},vendor:{HTC:"APA",Sprint:"Sprint"}}},os:{windows:{version:{ME:"4.90","NT 3.11":"NT3.51","NT 4.0":"NT4.0",2e3:"NT 5.0",XP:["NT 5.1","NT 5.2"],Vista:"NT 6.0",7:"NT 6.1",8:"NT 6.2",8.1:"NT 6.3",10:["NT 6.4","NT 10.0"],RT:"ARM"}}}},K={browser:[[/(opera\smini)\/([\w\.-]+)/i,/(opera\s[mobiletab]+).+version\/([\w\.-]+)/i,/(opera).+version\/([\w\.]+)/i,/(opera)[\/\s]+([\w\.]+)/i],[a,r],[/(opios)[\/\s]+([\w\.]+)/i],[[a,"Opera Mini"],r],[/\s(opr)\/([\w\.]+)/i],[[a,"Opera"],r],[/(kindle)\/([\w\.]+)/i,/(lunascape|maxthon|netfront|jasmine|blazer)[\/\s]?([\w\.]*)/i,/(avant\s|iemobile|slim|baidu)(?:browser)?[\/\s]?([\w\.]*)/i,/(?:ms|\()(ie)\s([\w\.]+)/i,/(rekonq)\/([\w\.]*)/i,/(chromium|flock|rockmelt|midori|epiphany|silk|skyfire|ovibrowser|bolt|iron|vivaldi|iridium|phantomjs|bowser|quark)\/([\w\.-]+)/i],[a,r],[/(trident).+rv[:\s]([\w\.]+).+like\sgecko/i],[[a,"IE"],r],[/(edge|edgios|edgea)\/((\d+)?[\w\.]+)/i],[[a,"Edge"],r],[/(yabrowser)\/([\w\.]+)/i],[[a,"Yandex"],r],[/(puffin)\/([\w\.]+)/i],[[a,"Puffin"],r],[/((?:[\s\/])uc?\s?browser|(?:juc.+)ucweb)[\/\s]?([\w\.]+)/i],[[a,"UCBrowser"],r],[/(comodo_dragon)\/([\w\.]+)/i],[[a,/_/g," "],r],[/(micromessenger)\/([\w\.]+)/i],[[a,"WeChat"],r],[/(qqbrowserlite)\/([\w\.]+)/i],[a,r],[/(QQ)\/([\d\.]+)/i],[a,r],[/m?(qqbrowser)[\/\s]?([\w\.]+)/i],[a,r],[/(BIDUBrowser)[\/\s]?([\w\.]+)/i],[a,r],[/(2345Explorer)[\/\s]?([\w\.]+)/i],[a,r],[/(MetaSr)[\/\s]?([\w\.]+)/i],[a],[/(LBBROWSER)/i],[a],[/xiaomi\/miuibrowser\/([\w\.]+)/i],[r,[a,"MIUI Browser"]],[/;fbav\/([\w\.]+);/i],[r,[a,"Facebook"]],[/headlesschrome(?:\/([\w\.]+)|\s)/i],[r,[a,"Chrome Headless"]],[/\swv\).+(chrome)\/([\w\.]+)/i],[[a,/(.+)/,"$1 WebView"],r],[/((?:oculus|samsung)browser)\/([\w\.]+)/i],[[a,/(.+(?:g|us))(.+)/,"$1 $2"],r],[/android.+version\/([\w\.]+)\s+(?:mobile\s?safari|safari)*/i],[r,[a,"Android Browser"]],[/(chrome|omniweb|arora|[tizenoka]{5}\s?browser)\/v?([\w\.]+)/i],[a,r],[/(dolfin)\/([\w\.]+)/i],[[a,"Dolphin"],r],[/((?:android.+)crmo|crios)\/([\w\.]+)/i],[[a,"Chrome"],r],[/(coast)\/([\w\.]+)/i],[[a,"Opera Coast"],r],[/fxios\/([\w\.-]+)/i],[r,[a,"Firefox"]],[/version\/([\w\.]+).+?mobile\/\w+\s(safari)/i],[r,[a,"Mobile Safari"]],[/version\/([\w\.]+).+?(mobile\s?safari|safari)/i],[r,a],[/webkit.+?(gsa)\/([\w\.]+).+?(mobile\s?safari|safari)(\/[\w\.]+)/i],[[a,"GSA"],r],[/webkit.+?(mobile\s?safari|safari)(\/[\w\.]+)/i],[a,[r,R.str,U.browser.oldsafari.version]],[/(konqueror)\/([\w\.]+)/i,/(webkit|khtml)\/([\w\.]+)/i],[a,r],[/(navigator|netscape)\/([\w\.-]+)/i],[[a,"Netscape"],r],[/(swiftfox)/i,/(icedragon|iceweasel|camino|chimera|fennec|maemo\sbrowser|minimo|conkeror)[\/\s]?([\w\.\+]+)/i,/(firefox|seamonkey|k-meleon|icecat|iceape|firebird|phoenix|palemoon|basilisk|waterfox)\/([\w\.-]+)$/i,/(mozilla)\/([\w\.]+).+rv\:.+gecko\/\d+/i,/(polaris|lynx|dillo|icab|doris|amaya|w3m|netsurf|sleipnir)[\/\s]?([\w\.]+)/i,/(links)\s\(([\w\.]+)/i,/(gobrowser)\/?([\w\.]*)/i,/(ice\s?browser)\/v?([\w\._]+)/i,/(mosaic)[\/\s]([\w\.]+)/i],[a,r]],cpu:[[/(?:(amd|x(?:(?:86|64)[_-])?|wow|win)64)[;\)]/i],[[t,"amd64"]],[/(ia32(?=;))/i],[[t,k.lowerize]],[/((?:i[346]|x)86)[;\)]/i],[[t,"ia32"]],[/windows\s(ce|mobile);\sppc;/i],[[t,"arm"]],[/((?:ppc|powerpc)(?:64)?)(?:\smac|;|\))/i],[[t,/ower/,"",k.lowerize]],[/(sun4\w)[;\)]/i],[[t,"sparc"]],[/((?:avr32|ia64(?=;))|68k(?=\))|arm(?:64|(?=v\d+;))|(?=atmel\s)avr|(?:irix|mips|sparc)(?:64)?(?=;)|pa-risc)/i],[[t,k.lowerize]]],device:[[/\((ipad|playbook);[\w\s\);-]+(rim|apple)/i],[i,e,[s,l]],[/applecoremedia\/[\w\.]+ \((ipad)/],[i,[e,"Apple"],[s,l]],[/(apple\s{0,1}tv)/i],[[i,"Apple TV"],[e,"Apple"]],[/(archos)\s(gamepad2?)/i,/(hp).+(touchpad)/i,/(hp).+(tablet)/i,/(kindle)\/([\w\.]+)/i,/\s(nook)[\w\s]+build\/(\w+)/i,/(dell)\s(strea[kpr\s\d]*[\dko])/i],[e,i,[s,l]],[/(kf[A-z]+)\sbuild\/.+silk\//i],[i,[e,"Amazon"],[s,l]],[/(sd|kf)[0349hijorstuw]+\sbuild\/.+silk\//i],[[i,R.str,U.device.amazon.model],[e,"Amazon"],[s,n]],[/\((ip[honed|\s\w*]+);.+(apple)/i],[i,e,[s,n]],[/\((ip[honed|\s\w*]+);/i],[i,[e,"Apple"],[s,n]],[/(blackberry)[\s-]?(\w+)/i,/(blackberry|benq|palm(?=\-)|sonyericsson|acer|asus|dell|meizu|motorola|polytron)[\s_-]?([\w-]*)/i,/(hp)\s([\w\s]+\w)/i,/(asus)-?(\w+)/i],[e,i,[s,n]],[/\(bb10;\s(\w+)/i],[i,[e,"BlackBerry"],[s,n]],[/android.+(transfo[prime\s]{4,10}\s\w+|eeepc|slider\s\w+|nexus 7|padfone)/i],[i,[e,"Asus"],[s,l]],[/(sony)\s(tablet\s[ps])\sbuild\//i,/(sony)?(?:sgp.+)\sbuild\//i],[[e,"Sony"],[i,"Xperia Tablet"],[s,l]],[/android.+\s([c-g]\d{4}|so[-l]\w+)\sbuild\//i],[i,[e,"Sony"],[s,n]],[/\s(ouya)\s/i,/(nintendo)\s([wids3u]+)/i],[e,i,[s,d]],[/android.+;\s(shield)\sbuild/i],[i,[e,"Nvidia"],[s,d]],[/(playstation\s[34portablevi]+)/i],[i,[e,"Sony"],[s,d]],[/(sprint\s(\w+))/i],[[e,R.str,U.device.sprint.vendor],[i,R.str,U.device.sprint.model],[s,n]],[/(lenovo)\s?(S(?:5000|6000)+(?:[-][\w+]))/i],[e,i,[s,l]],[/(htc)[;_\s-]+([\w\s]+(?=\))|\w+)*/i,/(zte)-(\w*)/i,/(alcatel|geeksphone|lenovo|nexian|panasonic|(?=;\s)sony)[_\s-]?([\w-]*)/i],[e,[i,/_/g," "],[s,n]],[/(nexus\s9)/i],[i,[e,"HTC"],[s,l]],[/d\/huawei([\w\s-]+)[;\)]/i,/(nexus\s6p)/i],[i,[e,"Huawei"],[s,n]],[/(microsoft);\s(lumia[\s\w]+)/i],[e,i,[s,n]],[/[\s\(;](xbox(?:\sone)?)[\s\);]/i],[i,[e,"Microsoft"],[s,d]],[/(kin\.[onetw]{3})/i],[[i,/\./g," "],[e,"Microsoft"],[s,n]],[/\s(milestone|droid(?:[2-4x]|\s(?:bionic|x2|pro|razr))?:?(\s4g)?)[\w\s]+build\//i,/mot[\s-]?(\w*)/i,/(XT\d{3,4}) build\//i,/(nexus\s6)/i],[i,[e,"Motorola"],[s,n]],[/android.+\s(mz60\d|xoom[\s2]{0,2})\sbuild\//i],[i,[e,"Motorola"],[s,l]],[/hbbtv\/\d+\.\d+\.\d+\s+\([\w\s]*;\s*(\w[^;]*);([^;]*)/i],[[e,k.trim],[i,k.trim],[s,F]],[/hbbtv.+maple;(\d+)/i],[[i,/^/,"SmartTV"],[e,"Samsung"],[s,F]],[/\(dtv[\);].+(aquos)/i],[i,[e,"Sharp"],[s,F]],[/android.+((sch-i[89]0\d|shw-m380s|gt-p\d{4}|gt-n\d+|sgh-t8[56]9|nexus 10))/i,/((SM-T\w+))/i],[[e,"Samsung"],i,[s,l]],[/smart-tv.+(samsung)/i],[e,[s,F],i],[/((s[cgp]h-\w+|gt-\w+|galaxy\snexus|sm-\w[\w\d]+))/i,/(sam[sung]*)[\s-]*(\w+-?[\w-]*)/i,/sec-((sgh\w+))/i],[[e,"Samsung"],i,[s,n]],[/sie-(\w*)/i],[i,[e,"Siemens"],[s,n]],[/(maemo|nokia).*(n900|lumia\s\d+)/i,/(nokia)[\s_-]?([\w-]*)/i],[[e,"Nokia"],i,[s,n]],[/android\s3\.[\s\w;-]{10}(a\d{3})/i],[i,[e,"Acer"],[s,l]],[/android.+([vl]k\-?\d{3})\s+build/i],[i,[e,"LG"],[s,l]],[/android\s3\.[\s\w;-]{10}(lg?)-([06cv9]{3,4})/i],[[e,"LG"],i,[s,l]],[/(lg) netcast\.tv/i],[e,i,[s,F]],[/(nexus\s[45])/i,/lg[e;\s\/-]+(\w*)/i,/android.+lg(\-?[\d\w]+)\s+build/i],[i,[e,"LG"],[s,n]],[/android.+(ideatab[a-z0-9\-\s]+)/i],[i,[e,"Lenovo"],[s,l]],[/linux;.+((jolla));/i],[e,i,[s,n]],[/((pebble))app\/[\d\.]+\s/i],[e,i,[s,Z]],[/android.+;\s(oppo)\s?([\w\s]+)\sbuild/i],[e,i,[s,n]],[/crkey/i],[[i,"Chromecast"],[e,"Google"]],[/android.+;\s(glass)\s\d/i],[i,[e,"Google"],[s,Z]],[/android.+;\s(pixel c)\s/i],[i,[e,"Google"],[s,l]],[/android.+;\s(pixel xl|pixel)\s/i],[i,[e,"Google"],[s,n]],[/android.+;\s(\w+)\s+build\/hm\1/i,/android.+(hm[\s\-_]*note?[\s_]*(?:\d\w)?)\s+build/i,/android.+(mi[\s\-_]*(?:one|one[\s_]plus|note lte)?[\s_]*(?:\d?\w?)[\s_]*(?:plus)?)\s+build/i,/android.+(redmi[\s\-_]*(?:note)?(?:[\s_]*[\w\s]+))\s+build/i],[[i,/_/g," "],[e,"Xiaomi"],[s,n]],[/android.+(mi[\s\-_]*(?:pad)(?:[\s_]*[\w\s]+))\s+build/i],[[i,/_/g," "],[e,"Xiaomi"],[s,l]],[/android.+;\s(m[1-5]\snote)\sbuild/i],[i,[e,"Meizu"],[s,l]],[/android.+a000(1)\s+build/i,/android.+oneplus\s(a\d{4})\s+build/i],[i,[e,"OnePlus"],[s,n]],[/android.+[;\/]\s*(RCT[\d\w]+)\s+build/i],[i,[e,"RCA"],[s,l]],[/android.+[;\/\s]+(Venue[\d\s]{2,7})\s+build/i],[i,[e,"Dell"],[s,l]],[/android.+[;\/]\s*(Q[T|M][\d\w]+)\s+build/i],[i,[e,"Verizon"],[s,l]],[/android.+[;\/]\s+(Barnes[&\s]+Noble\s+|BN[RT])(V?.*)\s+build/i],[[e,"Barnes & Noble"],i,[s,l]],[/android.+[;\/]\s+(TM\d{3}.*\b)\s+build/i],[i,[e,"NuVision"],[s,l]],[/android.+;\s(k88)\sbuild/i],[i,[e,"ZTE"],[s,l]],[/android.+[;\/]\s*(gen\d{3})\s+build.*49h/i],[i,[e,"Swiss"],[s,n]],[/android.+[;\/]\s*(zur\d{3})\s+build/i],[i,[e,"Swiss"],[s,l]],[/android.+[;\/]\s*((Zeki)?TB.*\b)\s+build/i],[i,[e,"Zeki"],[s,l]],[/(android).+[;\/]\s+([YR]\d{2})\s+build/i,/android.+[;\/]\s+(Dragon[\-\s]+Touch\s+|DT)(\w{5})\sbuild/i],[[e,"Dragon Touch"],i,[s,l]],[/android.+[;\/]\s*(NS-?\w{0,9})\sbuild/i],[i,[e,"Insignia"],[s,l]],[/android.+[;\/]\s*((NX|Next)-?\w{0,9})\s+build/i],[i,[e,"NextBook"],[s,l]],[/android.+[;\/]\s*(Xtreme\_)?(V(1[045]|2[015]|30|40|60|7[05]|90))\s+build/i],[[e,"Voice"],i,[s,n]],[/android.+[;\/]\s*(LVTEL\-)?(V1[12])\s+build/i],[[e,"LvTel"],i,[s,n]],[/android.+[;\/]\s*(V(100MD|700NA|7011|917G).*\b)\s+build/i],[i,[e,"Envizen"],[s,l]],[/android.+[;\/]\s*(Le[\s\-]+Pan)[\s\-]+(\w{1,9})\s+build/i],[e,i,[s,l]],[/android.+[;\/]\s*(Trio[\s\-]*.*)\s+build/i],[i,[e,"MachSpeed"],[s,l]],[/android.+[;\/]\s*(Trinity)[\-\s]*(T\d{3})\s+build/i],[e,i,[s,l]],[/android.+[;\/]\s*TU_(1491)\s+build/i],[i,[e,"Rotor"],[s,l]],[/android.+(KS(.+))\s+build/i],[i,[e,"Amazon"],[s,l]],[/android.+(Gigaset)[\s\-]+(Q\w{1,9})\s+build/i],[e,i,[s,l]],[/\s(tablet|tab)[;\/]/i,/\s(mobile)(?:[;\/]|\ssafari)/i],[[s,k.lowerize],e,i],[/(android[\w\.\s\-]{0,9});.+build/i],[i,[e,"Generic"]]],engine:[[/windows.+\sedge\/([\w\.]+)/i],[r,[a,"EdgeHTML"]],[/(presto)\/([\w\.]+)/i,/(webkit|trident|netfront|netsurf|amaya|lynx|w3m)\/([\w\.]+)/i,/(khtml|tasman|links)[\/\s]\(?([\w\.]+)/i,/(icab)[\/\s]([23]\.[\d\.]+)/i],[a,r],[/rv\:([\w\.]{1,9}).+(gecko)/i],[r,a]],os:[[/microsoft\s(windows)\s(vista|xp)/i],[a,r],[/(windows)\snt\s6\.2;\s(arm)/i,/(windows\sphone(?:\sos)*)[\s\/]?([\d\.\s\w]*)/i,/(windows\smobile|windows)[\s\/]?([ntce\d\.\s]+\w)/i],[a,[r,R.str,U.os.windows.version]],[/(win(?=3|9|n)|win\s9x\s)([nt\d\.]+)/i],[[a,"Windows"],[r,R.str,U.os.windows.version]],[/\((bb)(10);/i],[[a,"BlackBerry"],r],[/(blackberry)\w*\/?([\w\.]*)/i,/(tizen)[\/\s]([\w\.]+)/i,/(android|webos|palm\sos|qnx|bada|rim\stablet\sos|meego|contiki)[\/\s-]?([\w\.]*)/i,/linux;.+(sailfish);/i],[a,r],[/(symbian\s?os|symbos|s60(?=;))[\/\s-]?([\w\.]*)/i],[[a,"Symbian"],r],[/\((series40);/i],[a],[/mozilla.+\(mobile;.+gecko.+firefox/i],[[a,"Firefox OS"],r],[/(nintendo|playstation)\s([wids34portablevu]+)/i,/(mint)[\/\s\(]?(\w*)/i,/(mageia|vectorlinux)[;\s]/i,/(joli|[kxln]?ubuntu|debian|suse|opensuse|gentoo|(?=\s)arch|slackware|fedora|mandriva|centos|pclinuxos|redhat|zenwalk|linpus)[\/\s-]?(?!chrom)([\w\.-]*)/i,/(hurd|linux)\s?([\w\.]*)/i,/(gnu)\s?([\w\.]*)/i],[a,r],[/(cros)\s[\w]+\s([\w\.]+\w)/i],[[a,"Chromium OS"],r],[/(sunos)\s?([\w\.\d]*)/i],[[a,"Solaris"],r],[/\s([frentopc-]{0,4}bsd|dragonfly)\s?([\w\.]*)/i],[a,r],[/(haiku)\s(\w+)/i],[a,r],[/cfnetwork\/.+darwin/i,/ip[honead]{2,4}(?:.*os\s([\w]+)\slike\smac|;\sopera)/i],[[r,/_/g,"."],[a,"iOS"]],[/(mac\sos\sx)\s?([\w\s\.]*)/i,/(macintosh|mac(?=_powerpc)\s)/i],[[a,"Mac OS"],[r,/_/g,"."]],[/((?:open)?solaris)[\/\s-]?([\w\.]*)/i,/(aix)\s((\d)(?=\.|\)|\s)[\w\.])*/i,/(plan\s9|minix|beos|os\/2|amigaos|morphos|risc\sos|openvms)/i,/(unix)\s?([\w\.]*)/i],[a,r]]},_=function(f,g){if(typeof f=="object"&&(g=f,f=o),!(this instanceof _))return new _(f,g).getResult();var w=f||(c&&c.navigator&&c.navigator.userAgent?c.navigator.userAgent:V),S=g?k.extend(K,g):K;return this.getBrowser=function(){var P={name:o,version:o};return R.rgx.call(P,w,S.browser),P.major=k.major(P.version),P},this.getCPU=function(){var P={architecture:o};return R.rgx.call(P,w,S.cpu),P},this.getDevice=function(){var P={vendor:o,model:o,type:o};return R.rgx.call(P,w,S.device),P},this.getEngine=function(){var P={name:o,version:o};return R.rgx.call(P,w,S.engine),P},this.getOS=function(){var P={name:o,version:o};return R.rgx.call(P,w,S.os),P},this.getResult=function(){return{ua:this.getUA(),browser:this.getBrowser(),engine:this.getEngine(),os:this.getOS(),device:this.getDevice(),cpu:this.getCPU()}},this.getUA=function(){return w},this.setUA=function(P){return w=P,this},this};_.VERSION=N,_.BROWSER={NAME:a,MAJOR:x,VERSION:r},_.CPU={ARCHITECTURE:t},_.DEVICE={MODEL:i,VENDOR:e,TYPE:s,CONSOLE:d,MOBILE:n,SMARTTV:F,TABLET:l,WEARABLE:Z,EMBEDDED:oe},_.ENGINE={NAME:a,VERSION:r},_.OS={NAME:a,VERSION:r},typeof T!==D?(typeof O!==D&&O.exports&&(T=O.exports=_),T.UAParser=_):b(3)?(p=function(){return _}.call(T,b,T,O),p!==o&&(O.exports=p)):c&&(c.UAParser=_);var Y=c&&(c.jQuery||c.Zepto);if(typeof Y!==D){var j=new _;Y.ua=j.getResult(),Y.ua.get=function(){return j.getUA()},Y.ua.set=function(f){j.setUA(f);var g=j.getResult();for(var w in g)Y.ua[w]=g[w]}}})(typeof window=="object"?window:this)},function(O,T){(function(b){O.exports=b}).call(T,{})},function(O,T,b){Object.defineProperty(T,"__esModule",{value:!0});var p=Object.assign||function(a){for(var s=1;s<arguments.length;s++){var e=arguments[s];for(var r in e)Object.prototype.hasOwnProperty.call(e,r)&&(a[r]=e[r])}return a},c=b(0),o=c.DEVICE_TYPES,N=c.defaultData,V=function(s){switch(s){case o.MOBILE:return{isMobile:!0};case o.TABLET:return{isTablet:!0};case o.SMART_TV:return{isSmartTV:!0};case o.CONSOLE:return{isConsole:!0};case o.WEARABLE:return{isWearable:!0};case o.BROWSER:return{isBrowser:!0};default:return N}},h=function(s,e,r,t,d){return{isBrowser:s,browserMajorVersion:e.major,browserFullVersion:e.version,browserName:e.name,engineName:r.name||!1,engineVersion:r.version,osName:t.name,osVersion:t.version,userAgent:d}},I=function(s,e,r,t){return p({},s,{vendor:e.vendor,model:e.model,os:r.name,osVersion:r.version,ua:t})},D=function(s,e,r,t){return{isSmartTV:s,engineName:e.name,engineVersion:e.version,osName:r.name,osVersion:r.version,userAgent:t}},M=function(s,e,r,t){return{isConsole:s,engineName:e.name,engineVersion:e.version,osName:r.name,osVersion:r.version,userAgent:t}},v=function(s,e,r,t){return{isWearable:s,engineName:e.name,engineVersion:e.version,osName:r.name,osVersion:r.version,userAgent:t}},x=T.getNavigatorInstance=function(){return typeof window<"u"&&(window.navigator||navigator)?window.navigator||navigator:!1},i=T.isIOS13Check=function(s){var e=x();return e&&e.platform&&(e.platform.indexOf(s)!==-1||e.platform==="MacIntel"&&e.maxTouchPoints>1&&!window.MSStream)};O.exports={checkType:V,broPayload:h,mobilePayload:I,stvPayload:D,consolePayload:M,wearPayload:v,getNavigatorInstance:x,isIOS13Check:i}}])})(ae);const gr=X("div",{class:"d-flex justify-center align-center"},[X("img",{class:"",src:fr})],-1),Er=X("hr",{class:"hr mb-4"},null,-1),yr={class:"text-center"},Tr={class:"text-error"},hr={key:1,class:"text-center"},Sr=X("span",{class:"text-h6 mt-4 text-white"},"Change Password",-1),Pr={__name:"login",setup(A){const O=/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[~!@#$%^&*()_+`\-={}:";'<>?,.\/]).{8,20}|(?=.*[a-zA-Z])(?=.*\d).{10,20}$/,T=ar(),b=Xs(),p=Zs(),c=Qs(),o=C(!1),N=C(),V=C(""),h=C(""),I=C(!1);C(""),C(0);const D=C(""),M=C(!1),v=C({status:"E"}),x=C({email:void 0,password:void 0,msg:void 0}),i=C({required:t=>!!t||"This field is required",checkpass:t=>O.test(t)||"Invalid password",confirmPassword:()=>v.value.newPassword===v.value.repassword||"Password does not match"});function a(){I.value=!0,c.dispatch("user/auth",{username:V.value,password:h.value}).then(t=>{let d=[{action:"manage",subject:"all"}];localStorage.setItem("userAbilities",JSON.stringify(d)),T.update(d),b.push({path:D.value||"/"}),I.value=!1,b.replace(p.query.to?String(p.query.to):"/")}).catch(t=>{x.value={email:"",password:"",msg:t},I.value=!1})}const s=()=>{var t;x.value={email:"",password:"",msg:""},(t=N.value)==null||t.validate().then(({valid:d})=>{d&&a()})},e=()=>{x.value={email:"",password:"",msg:"",passErr:"",rePassErr:"",oldPassErr:""},M.value=!1,v.value.oldPassword="",v.value.newPassword="",Object.assign({},v)},r=async()=>{if(!v.value.oldPassword||!v.value.newPassword||!v.value.repassword||v.value.repassword!=v.value.newPassword){O.test(v.value.newPassword)||(x.value.passErr="Invalid new password."),v.value.newPassword!=v.value.repassword&&(x.value.rePassErr="Password does not match."),this.editedItem.oldPassword||(x.value.oldPassErr="Invalid old password"),setTimeout(()=>{x.value={passErr:"",rePassErr:"",oldPassErr:""}},6e3);return}else{if(!O.test(v.value.newPassword))return;const t=ir(),d=await or(v.value.oldPassword,v.value.newPassword,t);d.result.isSuccess?(fe("Login password modified successfully"),v.value.oldPassword="",v.value.newPassword="",e()):fe(d.result.msg,"error")}};return(t,d)=>(ne(),ve(ie,{"no-gutters":"",class:"auth-wrapper",style:rr(`background: url(${G(br)})`)},{default:y(()=>[m(W,{cols:"12",lg:"12",class:we(["d-flex align-center justify-center",G(ae.exports.isMobile)?"px-3":""])},{default:y(()=>[m(ge,{flat:"","min-width":G(ae.exports.isMobile)?350:500,"max-width":500,class:we(["mt-12 mt-sm-0 pa-4 border",G(ae.exports.isMobile)?"px-0":""])},{default:y(()=>[m(le,null,{default:y(()=>[m(W,null,{default:y(()=>[gr]),_:1}),Er]),_:1}),m(le,null,{default:y(()=>[m(pr,{ref_key:"refVForm",ref:N,onSubmit:Js(s,["prevent"])},{default:y(()=>[m(ie,null,{default:y(()=>[m(W,{cols:"12 pb-4"},{default:y(()=>[m(J,{modelValue:V.value,"onUpdate:modelValue":d[0]||(d[0]=n=>V.value=n),label:"User Name",type:"username",rules:[G(Ee)],"error-messages":x.value.email,class:"mb-4"},null,8,["modelValue","rules","error-messages"])]),_:1}),m(W,{cols:"12 pb-4"},{default:y(()=>[m(J,{modelValue:h.value,"onUpdate:modelValue":d[1]||(d[1]=n=>h.value=n),label:"Password",rules:[G(Ee)],type:o.value?"text":"password","append-inner-icon":o.value?"tabler-eye-off":"tabler-eye","onClick:appendInner":d[2]||(d[2]=n=>o.value=!o.value),"error-messages":x.value.password,class:"pb-4 mb-4"},null,8,["modelValue","rules","type","append-inner-icon","error-messages"]),X("p",yr,[X("span",Tr,Ks(x.value.msg),1)]),I.value?(ne(),$s("div",hr,[m(er,{size:30,color:"primary",indeterminate:""})])):(ne(),ve(te,{key:0,block:"",type:"submit"},{default:y(()=>[re("Login")]),_:1}))]),_:1}),m(W,{cols:"12 pb-4"})]),_:1})]),_:1},8,["onSubmit"])]),_:1})]),_:1},8,["min-width","class"]),m(lr,{modelValue:M.value,"onUpdate:modelValue":d[9]||(d[9]=n=>M.value=n),"max-width":"500px"},{activator:y(({props:n})=>[]),default:y(()=>[m(ge,null,{default:y(()=>[m(nr,{class:"bg-primary"},{default:y(()=>[Sr]),_:1}),m(le,null,{default:y(()=>[m(dr,null,{default:y(()=>[m(ie,null,{default:y(()=>[m(W,{cols:"12",sm:"12",md:"12",class:"text-primary font-weitght-bold"},{default:y(()=>[re(" You need to update your password because this is the first time your are signing in,or because your password has expired. ")]),_:1})]),_:1}),m(ie,null,{default:y(()=>[m(W,{cols:"12",sm:"12",md:"12"},{default:y(()=>[m(J,{modelValue:v.value.oldPassword,"onUpdate:modelValue":d[3]||(d[3]=n=>v.value.oldPassword=n),label:"Current Password",type:o.value?"text":"password","append-inner-icon":o.value?"tabler-eye-off":"tabler-eye","onClick:appendInner":d[4]||(d[4]=n=>o.value=!o.value),rules:[i.value.required],"error-messages":x.value.oldPassErr},null,8,["modelValue","type","append-inner-icon","rules","error-messages"])]),_:1}),m(W,{cols:"12",sm:"12",md:"12"},{default:y(()=>[m(J,{modelValue:v.value.newPassword,"onUpdate:modelValue":d[5]||(d[5]=n=>v.value.newPassword=n),label:"New Password",type:o.value?"text":"password","append-inner-icon":o.value?"tabler-eye-off":"tabler-eye","onClick:appendInner":d[6]||(d[6]=n=>o.value=!o.value),rules:[i.value.required,i.value.checkpass],"error-messages":x.value.passErr},null,8,["modelValue","type","append-inner-icon","rules","error-messages"])]),_:1}),m(W,{cols:"12",sm:"12",md:"12"},{default:y(()=>[m(J,{modelValue:v.value.repassword,"onUpdate:modelValue":d[7]||(d[7]=n=>v.value.repassword=n),label:"Re-enter Password",type:o.value?"text":"password","append-inner-icon":o.value?"tabler-eye-off":"tabler-eye","onClick:appendInner":d[8]||(d[8]=n=>o.value=!o.value),rules:[i.value.required,i.value.confirmPassword],"error-messages":x.value.rePassErr},null,8,["modelValue","type","append-inner-icon","rules","error-messages"])]),_:1}),m(W,{cols:"12",sm:"12",md:"12"},{default:y(()=>[m(ur,{label:"Note",color:"warning","no-resize":"",rows:"5",readonly:"",variant:"outlined",focused:!0,"model-value":G(sr).passTips},null,8,["model-value"])]),_:1})]),_:1})]),_:1})]),_:1}),m(tr,null,{default:y(()=>[m(cr),m(te,{color:"blue-darken-1",variant:"text",onClick:e},{default:y(()=>[re(" Cancel ")]),_:1}),m(te,{color:"blue-darken-1",variant:"text",onClick:r},{default:y(()=>[re(" Save ")]),_:1})]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1},8,["class"])]),_:1},8,["style"]))}};typeof be=="function"&&be(Pr);export{Pr as default};