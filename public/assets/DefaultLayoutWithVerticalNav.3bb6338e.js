import{t as m,i as u,r as p,o as n,b as a,w as t,m as d,q as o,D as b,p as e,C as f,ba as _,T as h,e as g,bb as v}from"./index.84400ea8.js";import C from"./Footer.bf90950e.js";import V from"./NavbarThemeSwitcher.7369cdbc.js";import w from"./UserProfile.b19c38a8.js";import{V as y}from"./VTextarea.d0bbf08b.js";import"./useAppAbility.3e9b8b60.js";import"./index.c0fab4f2.js";import"./VBadge.1e282c4e.js";import"./VDialog.e12e96f6.js";import"./VRow.5bc57025.js";const k=[{title:"Reports",icon:{icon:"tabler-home-2"},children:[{title:"Dashboard",to:{name:"reports-dashboard"}},{title:"Countries",to:"reports-countries"},{title:"Providers",to:"reports-providers"},{title:"Customers",to:"reports-customers"},{title:"MccMnc",to:"reports-mccmnc"},{title:"Senders",to:"reports-senders"}]},{title:"Last Messages",icon:{icon:"tabler-message-code"},to:"last-messages"},{title:"Issues",icon:{icon:"tabler-bug-filled"},to:{name:"incident-mobile"}},{title:"Connections",to:"dlr",icon:{icon:"tabler-cloud-data-connection"}},{title:"Routing",to:{name:"hlr"},icon:{icon:"tabler-route"}},{title:"Pricing",to:"billing",icon:{icon:"tabler-moneybag"}},{title:"Billing",to:{name:"search-ms"},icon:{icon:"tabler-report-money"}},{title:"Clearing",to:{name:"search-ms"},icon:{icon:"tabler-clear-all"}},{title:"Quality Routes",to:{name:"search-ms"},icon:{icon:"tabler-router"}},{title:"Configuration",to:{name:"search-ms"},icon:{icon:"tabler-settings-cog"}}],B={class:"d-flex h-100 align-center"},F={__name:"DefaultLayoutWithVerticalNav",setup(R){const{appRouteTransition:r,isLessThanOverlayNavBreakpoint:s}=m(),{width:c}=u();return(x,N)=>{const l=p("RouterView");return n(),a(o(v),{"nav-items":o(k)},{navbar:t(({toggleVerticalOverlayNavActive:i})=>[d("div",B,[o(s)(o(c))?(n(),a(b,{key:0,icon:"",variant:"text",color:"default",class:"ms-n3",size:"small",onClick:T=>i(!0)},{default:t(()=>[e(f,{icon:"tabler-menu-2",size:"24"})]),_:2},1032,["onClick"])):_("",!0),e(y),e(V),e(w)])]),footer:t(()=>[e(C)]),default:t(()=>[e(l,null,{default:t(({Component:i})=>[e(h,{name:o(r),mode:"out-in"},{default:t(()=>[(n(),a(g(i)))]),_:2},1032,["name"])]),_:1})]),_:1},8,["nav-items"])}}};export{F as default};