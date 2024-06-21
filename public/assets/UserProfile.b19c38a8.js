import{y as I,E as y,G as v,H as x,I as C,J as _,o as k,b as A,w as r,p as e,K as p,C as a,L as N,M as U,N as u,O as w,P as i,x as f,Q as E,R as V,m as g,S as T,U as m,D as h}from"./index.84400ea8.js";import{u as S,U as L}from"./useAppAbility.3e9b8b60.js";import{n as b}from"./index.c0fab4f2.js";import{V as z,a as P}from"./VBadge.1e282c4e.js";import{V as D}from"./VDialog.e12e96f6.js";import{V as q,a as B,b as R,c as G,d,e as H}from"./VRow.5bc57025.js";import{a as M,b as O,V as Z}from"./VTextarea.d0bbf08b.js";const{mapGetters:oe,mapActions:j}=y("user"),c=/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[~!@#$%^&*()_+`\-={}:";'<>?,.\/]).{8,20}|(?=.*[a-zA-Z])(?=.*\d).{10,20}$/,F={components(){},data(){return{getAuthName:v,getUserName:x,ability:S(),isPasswordVisible:!1,initialAbility:C,dialog:!1,menu:!1,editedItem:{status:"E"},rules:{required:l=>!!l||"This field is required",checkpass:l=>c.test(l)||"Invalid password",confirmPassword:()=>this.editedItem.newPassword===this.editedItem.repassword||"Password does not match"},errors:{},userId:_(),tips:"Password rules: At least eight characters with mixed-case alphabetic characters,numberals and special characters,or at least ten characters with combinations of mixed-case alphabetic characters and either numerals or special characters."}},watch:{dialog(l){l||this.close()},dialogDelete(l){l||this.closeDelete()},searchRole(l){}},methods:{...j(["logout"]),logoutAction(){this.logout(),localStorage.removeItem("userData"),localStorage.removeItem("accessToken"),this.$router.push("/login").then(()=>{localStorage.removeItem("userAbilities"),this.ability.update(this.initialAbility)}),location.reload()},close(){this.dialog=!1,this.menu=!1,this.$nextTick(()=>{this.editedItem=Object.assign({},this.defaultItem),this.editedIndex=-1})},async save(){if(!this.editedItem.oldPassword||!this.editedItem.newPassword||!this.editedItem.repassword||this.editedItem.repassword!=this.editedItem.newPassword){c.test(this.editedItem.newPassword)||(this.errors.passErr="Invalid new password."),this.editedItem.newPassword!=this.editedItem.repassword&&(this.errors.rePassErr="Password does not match."),this.editedItem.oldPassword||(this.errors.oldPassErr="Invalid old password"),setTimeout(()=>{this.errors={passErr:"",rePassErr:"",oldPassErr:""}},6e3);return}else{if(!c.test(this.editedItem.newPassword))return;const l=await L(this.editedItem.oldPassword,this.editedItem.newPassword,this.userId);l.result.isSuccess?(b("Login password modified successfully"),this.editedItem.oldPassword="",this.editedItem.newPassword="",this.$emit("close",!0),this.close()):b(l.result.msg,"error")}}}},J=g("span",{class:"text-h6 mt-4"},"Change Password",-1);function K(l,t,Q,W,s,n){return k(),A(P,{dot:"",location:"bottom right","offset-x":"3","offset-y":"3",bordered:"",color:"success"},{default:r(()=>[e(p,{class:"cursor-pointer",color:"primary",variant:"tonal"},{default:r(()=>[e(a,{icon:"tabler-user",size:"32"}),e(N,{activator:"parent",width:"230",location:"bottom end",offset:"14px",modelValue:s.menu,"onUpdate:modelValue":t[7]||(t[7]=o=>s.menu=o)},{default:r(()=>[e(U,null,{default:r(()=>[e(u,null,{prepend:r(()=>[e(z,{start:""},{default:r(()=>[e(P,{dot:"",location:"bottom right","offset-x":"3","offset-y":"3",color:"success"},{default:r(()=>[e(p,{color:"primary",variant:"tonal"},{default:r(()=>[e(a,{icon:"tabler-user",size:"64"})]),_:1})]),_:1})]),_:1})]),default:r(()=>[e(w,{class:"font-weight-semibold"},{default:r(()=>[i(f(s.getUserName()),1)]),_:1}),e(E,null,{default:r(()=>[i(f(s.getAuthName()?s.getAuthName():s.getUserName()),1)]),_:1})]),_:1}),e(V,{class:"my-2"}),e(u,null,{default:r(()=>[e(D,{modelValue:s.dialog,"onUpdate:modelValue":t[6]||(t[6]=o=>s.dialog=o),"max-width":"500px"},{activator:r(({props:o})=>[e(a,{class:"me-2 cursor-pointer",icon:"tabler-key",size:"22"}),g("span",T(o,{class:"cursor-pointer"}),"Change Password",16)]),default:r(()=>[e(q,null,{default:r(()=>[e(B,null,{default:r(()=>[J]),_:1}),e(R,null,{default:r(()=>[e(M,null,{default:r(()=>[e(G,null,{default:r(()=>[e(d,{cols:"12",sm:"12",md:"12"},{default:r(()=>[e(m,{modelValue:s.editedItem.oldPassword,"onUpdate:modelValue":t[0]||(t[0]=o=>s.editedItem.oldPassword=o),label:"Current Password",type:s.isPasswordVisible?"text":"password","append-inner-icon":s.isPasswordVisible?"tabler-eye-off":"tabler-eye","onClick:appendInner":t[1]||(t[1]=o=>s.isPasswordVisible=!s.isPasswordVisible),rules:[s.rules.required],"error-messages":s.errors.oldPassErr},null,8,["modelValue","type","append-inner-icon","rules","error-messages"])]),_:1}),e(d,{cols:"12",sm:"12",md:"12"},{default:r(()=>[e(m,{modelValue:s.editedItem.newPassword,"onUpdate:modelValue":t[2]||(t[2]=o=>s.editedItem.newPassword=o),label:"New Password",type:s.isPasswordVisible?"text":"password","append-inner-icon":s.isPasswordVisible?"tabler-eye-off":"tabler-eye","onClick:appendInner":t[3]||(t[3]=o=>s.isPasswordVisible=!s.isPasswordVisible),rules:[s.rules.required,s.rules.checkpass],"error-messages":s.errors.passErr},null,8,["modelValue","type","append-inner-icon","rules","error-messages"])]),_:1}),e(d,{cols:"12",sm:"12",md:"12"},{default:r(()=>[e(m,{modelValue:s.editedItem.repassword,"onUpdate:modelValue":t[4]||(t[4]=o=>s.editedItem.repassword=o),label:"Re-enter Password",type:s.isPasswordVisible?"text":"password","append-inner-icon":s.isPasswordVisible?"tabler-eye-off":"tabler-eye","onClick:appendInner":t[5]||(t[5]=o=>s.isPasswordVisible=!s.isPasswordVisible),rules:[s.rules.required,s.rules.confirmPassword],"error-messages":s.errors.rePassErr},null,8,["modelValue","type","append-inner-icon","rules","error-messages"])]),_:1}),e(d,{cols:"12",sm:"12",md:"12"},{default:r(()=>[e(O,{label:"Note",color:"warning","no-resize":"",rows:"5",readonly:"",variant:"outlined",focused:!0,"model-value":s.tips},null,8,["model-value"])]),_:1})]),_:1})]),_:1})]),_:1}),e(H,null,{default:r(()=>[e(Z),e(h,{color:"blue-darken-1",variant:"text",onClick:n.close},{default:r(()=>[i(" Cancel ")]),_:1},8,["onClick"]),e(h,{color:"blue-darken-1",variant:"text",onClick:n.save},{default:r(()=>[i(" Save ")]),_:1},8,["onClick"])]),_:1})]),_:1})]),_:1},8,["modelValue"])]),_:1}),e(V,{class:"my-2"}),e(u,{link:"",onClick:n.logoutAction},{prepend:r(()=>[e(a,{class:"me-2",icon:"tabler-logout",size:"22"})]),default:r(()=>[e(w,null,{default:r(()=>[i("Logout")]),_:1})]),_:1},8,["onClick"])]),_:1})]),_:1},8,["modelValue"])]),_:1})]),_:1})}const le=I(F,[["render",K]]);export{le as default};