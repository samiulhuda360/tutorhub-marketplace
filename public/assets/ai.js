// TutorHub AI helpers: offer intake, bio and cover-letter drafts.
// Every result fills a form the user still reviews and submits themselves.

(function () {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  async function post(url, data) {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify(data),
    });
    const body = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(body.message || 'Something went wrong (' + res.status + ').');
    return body;
  }

  function sourceLabel(source) {
    return { ai: 'Filled by AI', 'ai+rules': 'Filled by AI (gaps filled by rules)', rules: 'Filled by matching your words (AI is off)' }[source] || '';
  }

  function mark(el, cls) {
    if (!el) return;
    el.classList.remove('ai-filled', 'ai-missing');
    if (cls) el.classList.add(cls);
  }

  // ---- Offer intake: parent's own words -> the offer form
  document.querySelectorAll('[data-ai-intake]').forEach((panel) => {
    const form = document.querySelector(panel.dataset.form || 'form[data-offer-form]');
    const status = panel.querySelector('[data-ai-status]');
    const button = panel.querySelector('[data-ai-run]');
    const input = panel.querySelector('textarea');

    button.addEventListener('click', async () => {
      if (!input.value.trim()) { input.focus(); return; }
      button.disabled = true;
      status.textContent = 'Reading your request…';
      try {
        const d = await post(panel.dataset.url, { request: input.value });
        const set = (name, value) => {
          const el = form.elements[name];
          if (!el) return;
          if (value === null || value === undefined || value === '') { mark(el, null); return; }
          el.value = value;
          mark(el, 'ai-filled');
        };
        ['title', 'description', 'student_class', 'area_id', 'category_id', 'tuition_type',
          'tutor_gender_preference', 'days_per_week', 'salary', 'location'].forEach((f) => set(f, d[f]));
        form.querySelectorAll('input[name="subjects[]"]').forEach((box) => {
          box.checked = (d.subjects || []).includes(box.value);
        });
        (d.missing || []).forEach((f) => mark(form.elements[f === 'subjects' ? 'subjects[]' : f]?.[0] || form.elements[f], 'ai-missing'));
        status.textContent = sourceLabel(d.source)
          + (d.missing && d.missing.length ? ' · please fill: ' + d.missing.map((m) => m.replace('_id', '').replace(/_/g, ' ')).join(', ') : ' · check it and post');
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
      } catch (e) {
        status.textContent = e.message;
      } finally {
        button.disabled = false;
      }
    });
  });

  // ---- Draft buttons: bio (profile form) and cover letter (apply form)
  document.querySelectorAll('[data-ai-draft]').forEach((button) => {
    const form = button.closest('form');
    const target = form.querySelector(button.dataset.target);
    const status = form.querySelector('[data-ai-status]');

    button.addEventListener('click', async () => {
      if (target.value.trim() && !confirm('Replace what you have written with a new draft?')) return;
      button.disabled = true;
      status.textContent = 'Writing a draft…';
      try {
        const payload = {};
        new FormData(form).forEach((value, key) => {
          if (key === '_token' || key === '_method') return;
          if (key.endsWith('[]')) (payload[key.slice(0, -2)] ||= []).push(value);
          else payload[key] = value;
        });
        const d = await post(button.dataset.url, payload);
        target.value = d.text;
        mark(target, 'ai-filled');
        status.textContent = (d.source === 'ai' ? 'AI draft' : 'Template draft (AI is off)') + ' · edit it before you save';
      } catch (e) {
        status.textContent = e.message;
      } finally {
        button.disabled = false;
      }
    });
  });
})();
