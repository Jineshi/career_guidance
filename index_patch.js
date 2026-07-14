// CareerGuide Index Patch - adds Entrance Exams to Learn More modal
// SETUP: Copy to htdocs/career_guidance/ then add before </body> in index.html:
//   <script src="index_patch.js"></script>

const careerExams = {
    'Software Developer':        [{name:'JEE Main',level:'National',eligibility:'12th PCM',desc:'B.Tech CSE at NITs/IIITs'},{name:'MH-CET',level:'State MH',eligibility:'12th PCM 45%+',desc:'B.Tech in Maharashtra'}],
    'Data Scientist':            [{name:'JEE Main',level:'National',eligibility:'12th PCM',desc:'B.Tech CSE/Data Science at NITs'},{name:'GATE',level:'National',eligibility:'B.Tech',desc:'M.Tech and PSU jobs'}],
    'Machine Learning Engineer': [{name:'JEE Main',level:'National',eligibility:'12th PCM',desc:'B.Tech CSE/AI at NITs'},{name:'JEE Advanced',level:'National',eligibility:'JEE Main top 2.5L',desc:'IIT B.Tech CSE/AI'},{name:'GATE',level:'National',eligibility:'B.Tech',desc:'M.Tech at IITs/NITs'}],
    'Web Developer':             [{name:'JEE Main',level:'National',eligibility:'12th PCM',desc:'B.Tech CSE at NITs'},{name:'MH-CET',level:'State MH',eligibility:'12th PCM',desc:'Maharashtra B.Tech'}],
    'Cybersecurity Analyst':     [{name:'JEE Main',level:'National',eligibility:'12th PCM',desc:'B.Tech CSE/Cyber'},{name:'GATE',level:'National',eligibility:'B.Tech',desc:'M.Tech Cybersecurity'}],
    'UX/UI Designer':            [{name:'UCEED',level:'National',eligibility:'12th any',desc:'B.Des at IITs'},{name:'CEED',level:'National',eligibility:'Graduation',desc:'M.Des at IITs'},{name:'NATA',level:'National',eligibility:'12th 50%+',desc:'B.Arch'}],
    'Graphic Designer':          [{name:'UCEED',level:'National',eligibility:'12th any',desc:'B.Des at IITs'},{name:'CEED',level:'National',eligibility:'Graduation',desc:'M.Des at IITs'}],
    'Digital Marketing Manager': [],
    'Marketing Specialist':      [],
    'Product Manager':           [{name:'CAT',level:'National',eligibility:'Graduation 50%+',desc:'MBA at IIMs for PM roles'}],
    'Content Writer':            [],
    'Registered Nurse':          [{name:'NEET',level:'National',eligibility:'12th PCB 50%+',desc:'B.Sc Nursing at govt colleges'}],
    'Pharmacist':                [{name:'NEET',level:'National',eligibility:'12th PCB 50%+',desc:'B.Pharm at govt colleges'}],
    'Civil Engineer':            [{name:'JEE Main',level:'National',eligibility:'12th PCM',desc:'B.Tech Civil at NITs'},{name:'JEE Advanced',level:'National',eligibility:'JEE Main top 2.5L',desc:'IIT B.Tech Civil'},{name:'MH-CET',level:'State MH',eligibility:'12th PCM',desc:'Maharashtra B.Tech Civil'}],
    'Mechanical Engineer':       [{name:'JEE Main',level:'National',eligibility:'12th PCM',desc:'B.Tech Mechanical at NITs'},{name:'JEE Advanced',level:'National',eligibility:'JEE Main top 2.5L',desc:'IIT Mechanical'},{name:'GATE',level:'National',eligibility:'B.Tech',desc:'M.Tech/PSU jobs'}],
    'Financial Advisor':         [{name:'CA Foundation',level:'National',eligibility:'12th any',desc:'ICAI Chartered Accountant path'},{name:'CAT',level:'National',eligibility:'Graduation',desc:'MBA Finance at IIMs'}],
    'Elementary Teacher':        [{name:'CTET',level:'National',eligibility:'D.El.Ed/B.Ed',desc:'Central Teacher Eligibility Test'},{name:'TET',level:'State',eligibility:'D.El.Ed/B.Ed',desc:'State TET exam'}],
    'Physical Therapist':        [{name:'NEET',level:'National',eligibility:'12th PCB 50%+',desc:'B.Sc Physiotherapy at govt colleges'}],
    'Accountant':                [{name:'CA Foundation',level:'National',eligibility:'12th any',desc:'ICAI Chartered Accountant entry'}],
    'Police Officer':            [{name:'UPSC Civil Services',level:'National',eligibility:'Graduation any',desc:'For IPS cadre'},{name:'State PSC',level:'State',eligibility:'Graduation',desc:'State police exams'}],
};

window.showCareerDetail = function(careerName) {
    const career = (window.allCareers||[]).find(c=>c.name===careerName);
    if (!career) return;
    const exams = careerExams[careerName]||[];
    const isLoggedIn = localStorage.getItem('isLoggedIn')==='true';

    const examsHtml = exams.length > 0
        ? exams.map(ex=>`
            <div style="background:#f8f9fa;border-radius:10px;padding:1rem;margin-bottom:0.8rem;display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
                <div style="flex:1;">
                    <strong style="color:#2c3e50;">${ex.name}</strong>
                    <span style="background:#e9f4ff;color:#0066cc;font-size:0.75rem;padding:0.15rem 0.5rem;border-radius:10px;margin-left:0.4rem;">${ex.level}</span>
                    <p style="color:#555;font-size:0.88rem;margin-top:0.3rem;">${ex.desc}</p>
                    <p style="color:#888;font-size:0.82rem;">Eligibility: ${ex.eligibility}</p>
                </div>
                ${isLoggedIn?`<button onclick="saveExamToAccount(this,'${ex.name.replace(/'/g,"\\'")}','${careerName.replace(/'/g,"\\'")}','${ex.level}','${ex.eligibility.replace(/'/g,"\\'")}')" style="background:#667eea;color:white;border:none;padding:0.4rem 0.9rem;border-radius:8px;cursor:pointer;font-size:0.82rem;font-weight:600;white-space:nowrap;flex-shrink:0;">📌 Save Exam</button>`:''}
            </div>`).join('')
        :`<div style="background:#f0fff4;border:1px solid #b7e4c7;border-radius:10px;padding:1.2rem;color:#2d6a4f;">
            ✅ <strong>No competitive entrance exam required!</strong><br><br>
            Enter this career through: relevant degree, portfolio/internships, or online certifications.
          </div>`;

    document.getElementById('modalContent').innerHTML=`
        <div class="career-icon">${career.icon}</div>
        <h2>${career.name}</h2>
        <p style="margin-bottom:1.5rem;color:#666;">${career.detailedDescription}</p>
        <div class="salary-info">
            <h4 style="color:#2c3e50;margin-bottom:0.5rem;">💰 Salary Range</h4>
            <p style="font-size:1.2rem;font-weight:600;color:#28a745;">${career.salary}</p>
        </div>
        <div class="skills-section" style="margin-top:1.5rem;">
            <h4 style="color:#2c3e50;margin-bottom:1rem;">🎯 Required Skills</h4>
            <div class="skills-list">${career.skills.map(s=>`<span class="skill-tag">${s}</span>`).join('')}</div>
        </div>
        <div style="margin-top:1.8rem;">
            <h4 style="color:#2c3e50;margin-bottom:1rem;">🎓 Entrance Exams / Admission Requirements</h4>
            ${examsHtml}
            ${!isLoggedIn&&exams.length>0?`<p style="color:#888;font-size:0.84rem;margin-top:0.5rem;"><a href="login.html" style="color:#667eea;font-weight:600;">Log in</a> to save exams to your tracker.</p>`:''}
        </div>
        <div style="margin-top:2rem;text-align:center;">
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-bottom:1.5rem;">
                <button class="btn" onclick="addToCareerInterests('${career.name}')" style="background:#28a745;padding:1rem 1.5rem;">❤️ I am Interested</button>
                <button class="btn" onclick="takeCareerTest('${career.name}')" style="background:#667eea;padding:1rem 1.5rem;">📝 Take Test</button>
            </div>
            <button class="btn" onclick="window.open('${career.youtube}','_blank')" style="padding:1rem 2rem;background:#dc3545;">🎥 Watch on YouTube</button>
        </div>`;

    document.getElementById('careerModal').style.display='block';
};

window.saveExamToAccount = async function(btn, examName, careerName, level, eligibility) {
    const s = JSON.parse(localStorage.getItem('currentUser')||'{}');
    if (!s.username) { alert('Please log in first!'); return; }
    btn.disabled=true; btn.textContent='Saving...';
    try {
        const r = await fetch('save_exam.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({username:s.username,exam_name:examName,career_name:careerName,exam_level:level,eligibility})});
        const d = await r.json();
        if (d.success||d.error==='Exam already saved') {
            btn.textContent='✅ Saved!'; btn.style.background='#28a745';
            const t=document.createElement('div');
            t.textContent=`✅ "${examName}" added to your exam tracker!`;
            Object.assign(t.style,{position:'fixed',bottom:'2rem',left:'50%',transform:'translateX(-50%)',background:'#28a745',color:'white',padding:'0.8rem 1.5rem',borderRadius:'12px',fontWeight:'600',zIndex:'9999',boxShadow:'0 4px 15px rgba(0,0,0,0.2)'});
            document.body.appendChild(t); setTimeout(()=>t.remove(),3000);
        } else { btn.textContent='⚠️ Error'; btn.disabled=false; }
    } catch(e) { btn.textContent='⚠️ Error'; btn.disabled=false; }
};