import fs from 'fs';
import path from 'path';
import { execFileSync } from 'child_process';

const projectRoot = path.resolve('/Users/Birittany/Documents/New project 2/sosparkcon-main');
const seederPath = path.join(projectRoot, 'database/seeders/SouthernSparkSeeder.php');
const consolePath = path.join(projectRoot, 'routes/console.php');
const outputRoot = path.join(projectRoot, 'public/share-cards/generated');
const svgDir = path.join(outputRoot, 'svg');
const pngDir = path.join(outputRoot, 'png');
const speakerBlueprintPath = path.join(projectRoot, 'public/share-cards/speaker-blueprint.svg');
const sparkLogoPath = path.join(projectRoot, 'public/favicon-spark.png');
const speakerBackgroundPath = path.join(projectRoot, 'public/share-cards/speaker-background.png');

fs.mkdirSync(svgDir, { recursive: true });
fs.mkdirSync(pngDir, { recursive: true });

const seeder = fs.readFileSync(seederPath, 'utf8');
const consoleFile = fs.readFileSync(consolePath, 'utf8');
const speakerBlueprintDataUri = toDataUri(speakerBlueprintPath);
const sparkLogoDataUri = toDataUri(sparkLogoPath);
const speakerBackgroundDataUri = toDataUri(speakerBackgroundPath);
const dayMap = {
  day1: { label: 'Day 1', date: 'Jun 11, 2026' },
  day2: { label: 'Day 2', date: 'Jun 12, 2026' },
};

const speakerPhotoUrls = new Map(Object.entries({
  'krystal chatman': 'https://southernspark.app/storage/profiles/PTGwIg8qWdi7jb8bj7pPJOSmbnQgsc3PmWTIxle6.jpg',
  'southern spark admin': 'https://southernspark.app/storage/profiles/difTeA0MS6MVoeUwEr19fzu1A8kII904uTwZuR2U.png',
  'reginald matthews': 'https://southernspark.app/storage/profiles/yFKzIClDELFsIuAZX6OuIGCzWiwvP5WP478L6Kkk.jpg',
  'shauna waters': 'https://southernspark.app/storage/profiles/2RvUXGVpXuI38mvRMacNmoZOvMtOA6ZIemCEYeIT.jpg',
  'shalando jones': 'https://southernspark.app/storage/profiles/PzLezpLLzksGQiFnNNSrYcGQyNqkUHYmuzx9K7QZ.png',
  'benjamin beng goudy': 'https://southernspark.app/storage/profiles/uB35GrfbuRxYL8M5mkkfPjsCmOcgoFete5WtrpNz.jpg',
  'william hill': 'https://southernspark.app/storage/profiles/5VXGOaa41FzhBkC6XasirhKV4bDQFAiIAABXt5e1.jpg',
  'yolanda edmonds': 'https://southernspark.app/storage/profiles/7v8MukY8cvqMcmh2R3rvwQ2gkLElCdX9d9ARpXgF.png',
  'heather white': 'https://southernspark.app/storage/profiles/bjtkLjxRD16gAcjhKvRHL0inuiDsK56ZbQdKn3UG.png',
  'chris chism': 'https://southernspark.app/storage/profiles/uv9B7GrctHaIsUgd7X7r6rTGk0x7y8YZGbL0QtVc.webp',
  'bob buseck': 'https://southernspark.app/storage/profiles/zuHMuzPIqUzxOffUwglRGICeeSGue6kKk4JlZkrs.png',
  'luciano c oviedo': 'https://southernspark.app/storage/profiles/hCjDkGzQl7RmxaudWY6BNDNGyFQjBipbWxTftYkY.jpg',
  'carlos s ewing phd': 'https://southernspark.app/storage/profiles/uLyyk10BhOOOIy5wpXX9qPV0dVpFvyQUsj89WHZP.jpg',
  'tony jeff': 'https://southernspark.app/storage/profiles/UEJTimzCK8jiJNqlmDr5qQEYgpo7XYUlvCXjmveR.jpg',
  'shelley thompson': 'https://southernspark.app/storage/profiles/KAmbmOIkz8idoi5qA4DS406zCJGAZviiCOUCgFnX.jpg',
  'dr lorilyn thompson': 'https://southernspark.app/storage/profiles/jMYitTDtl645KxrCMAfYL8juZebiDiHHeWTX0PD6.png',
  'tara poolson': 'https://southernspark.app/storage/profiles/cf47EOe7ZamGM59dXm6TZzpOB43B4Ze0DDfPpnSe.png',
  'shivochie dinkins': 'https://southernspark.app/storage/profiles/YigQjr0zNzuO6ZWQKVVKzA1q2JHGTOME2egeqiCG.jpg',
  'amber sheppard': 'https://southernspark.app/storage/profiles/dqngDuH2SnGsOe67LAhD1x1a0MKe2WmBlp9ENMA1.jpg',
  'dr loretta moore': 'https://southernspark.app/storage/profiles/YWfLuXduHHdgKnBZVk0b0jutkXA21edPCUIMafWC.jpg',
  'lara taylor': 'https://southernspark.app/storage/profiles/0CIsc6i1ZI1Z54QxKdnNi3ZZPdq2ncfjwzsXQO3L.jpg',
  'stephanie triplett': 'https://southernspark.app/storage/profiles/zOE3nCh7jsLc93uuTmbEx5KlOt2MF7OkMsvsW7gk.jpg',
  'tianne brown': 'https://southernspark.app/storage/profiles/L5NX1bktvGyT9rcy8vzV5TXFCfvBSHC9IA3engOm.jpg',
  'trevor acy': 'https://southernspark.app/storage/profiles/nIs271gVvHsXLfphpgzmRmZBkjFBi9Mt2AwymKyW.jpg',
  'raymonda delaware': 'https://southernspark.app/storage/profiles/qzPx7kqxdovyzK2K5mZEPwbZyylkpsVK7s76fAXp.webp',
  'sonia daniels phd': 'https://southernspark.app/storage/profiles/DrqUj6kpNijNwVRfuxWLGMQHdCqOZkD0s9soeq6v.jpg',
  'william edd blake': 'https://southernspark.app/storage/profiles/FnjEOfOe7V4GDtUaXjb07Hraexarmo2jbpk2gwCa.png',
  'dr eva harvell': 'https://southernspark.app/storage/profiles/eBog0NDDQdwPVHMxk2I8DXCM6MmhLsPQLj1LFdNk.jpg',
  'gigi mims': 'https://southernspark.app/storage/profiles/q7Qj4lJAuPxtaSSgJg5NsscBWdz8EV1re9PQu02n.jpg',
})); 
const speakerPhotoAliases = new Map(Object.entries({
  'dr chris chism': 'chris chism',
  'dr loretta moore': 'dr loretta moore',
  'carlos ewing': 'carlos s ewing phd',
  'carlos s ewing': 'carlos s ewing phd',
  'luciano oviedo': 'luciano c oviedo',
  'benjamin "beng" goudy': 'benjamin beng goudy',
  'benjamin beng goudy': 'benjamin beng goudy',
  'raymonda placeholder': 'raymonda delaware',
}));
const photoDataUriCache = new Map();

const splitSpeakerMap = parseSplitSpeakerMap(consoleFile);
const sessionItems = parseSessionItems(seeder);
const speakerSessions = expandSpeakerSessions(sessionItems, splitSpeakerMap);

const manifest = [];

for (const entry of speakerSessions) {
  const filename = buildFilename(entry);
  const svgPath = path.join(svgDir, `${filename}.svg`);
  fs.writeFileSync(svgPath, buildSpeakerSvg(entry));
  manifest.push({
    speaker: entry.speakerName,
    session: entry.title,
    svg: svgPath,
    png: path.join(pngDir, `${filename}.png`),
  });
}

fs.writeFileSync(path.join(outputRoot, 'manifest.json'), JSON.stringify(manifest, null, 2));
console.log(`Generated ${manifest.length} SVG speaker cards in ${svgDir}`);

function parseSplitSpeakerMap(source) {
  const map = new Map();
  const sessionEntryRegex = /'((?:\\'|[^'])+)' => \[(.*?)\],/gs;
  const speakerRegex = /'name' => '((?:\\'|[^'])+)'/g;

  for (const match of source.matchAll(sessionEntryRegex)) {
    const title = unescapePhp(match[1]);
    const inner = match[2];
    const speakers = [];

    for (const speakerMatch of inner.matchAll(speakerRegex)) {
      speakers.push(unescapePhp(speakerMatch[1]));
    }

    if (speakers.length > 0) {
      map.set(title, speakers);
    }
  }

  return map;
}

function parseSessionItems(source) {
  const items = [];
  const blockRegex = /\[\s*\n((?:\s+'[^']+' => .*?\n)+)\s*\],/g;

  for (const match of source.matchAll(blockRegex)) {
    const block = match[1];
    const data = {};
    for (const line of block.split('\n')) {
      const kv = line.match(/'([^']+)' => (null|'((?:\\'|[^'])*)'|true|false),?/);
      if (!kv) continue;
      const [, key, raw, stringVal] = kv;
      if (raw === 'null') data[key] = null;
      else if (raw === 'true') data[key] = true;
      else if (raw === 'false') data[key] = false;
      else data[key] = unescapePhp(stringVal);
    }

    if (data.day && data.title && Object.prototype.hasOwnProperty.call(data, 'speaker')) {
      items.push(data);
    }
  }

  return items.filter((item) => item.speaker);
}

function expandSpeakerSessions(items, splitMap) {
  const expanded = [];

  for (const item of items) {
    const splitSpeakers = splitMap.get(item.title);
    const speakerNames = splitSpeakers ?? [item.speaker];

    for (const speakerName of speakerNames) {
      expanded.push({
        speakerName,
        speakerTitle: item.title_line || 'Speaker',
        speakerOrg: item.organization || 'Southern Spark',
        title: item.title,
        dayKey: item.day,
        startTime: item.start_time,
        location: item.location || 'Southern Spark',
        detailLabel: `${formatTime(item.start_time)} - ${formatTime(item.end_time)}  |  ${dayMap[item.day]?.date || ''}  |  ${item.location || 'Southern Spark'}`,
      });
    }
  }

  return expanded;
}

function buildFilename(entry) {
  const speakerPart = slug(entry.speakerName);
  const titlePart = slug(entry.title);
  const dayPart = slug(dayMap[entry.dayKey]?.label || entry.dayKey || 'day');
  const timePart = slug(formatTime(entry.startTime || '00:00'));
  return `${speakerPart}--${titlePart}--${dayPart}-${timePart}`;
}

function buildSpeakerSvg(entry) {
  const profilePhoto = resolveSpeakerPhotoDataUri(entry.speakerName);
  const speakerNameLines = wrapText(entry.speakerName, 18, 2);
  const titleLayout = speakerTitleLayout(entry.title);
  const titleLines = titleLayout.lines;
  const speakerNameY = speakerNameLines.length === 1 ? 700 : 672;
  const titleX = 72;
  const titleY = speakerNameY + (speakerNameLines.length * 76) + 70;
  const titleBlockHeight = (Math.max(titleLines.length, 1) - 1) * titleLayout.lineHeight;
  const detailY = Math.min(titleY + titleBlockHeight + 92, 1184);
  const initials = initialsFor(entry.speakerName);

  const nameText = speakerNameLines.map((line, index) =>
    `<text x="72" y="${speakerNameY + (index * 72)}" font-size="64" font-weight="800" fill="#FFFFFF" font-family="'Avenir Next', Avenir, Montserrat, Arial, sans-serif">${escapeXml(line)}</text>`
  ).join('\n');

  const titleText = titleLines.map((line, index) =>
    `<text x="${titleX}" y="${titleY + (index * titleLayout.lineHeight)}" font-size="${titleLayout.fontSize}" font-weight="500" fill="#FFFFFF" font-family="'Avenir Next', Avenir, Montserrat, Arial, sans-serif">${escapeXml(line)}</text>`
  ).join('\n');

  const photoStage = profilePhoto
    ? `<g filter="url(#speaker-photo-shadow)">
    <image href="${profilePhoto}" x="44" y="370" width="236" height="236" preserveAspectRatio="xMidYMid slice" clip-path="url(#speaker-photo-clip)"/>
  </g>`
    : `<g filter="url(#speaker-photo-shadow)">
    <circle cx="162" cy="488" r="118" fill="url(#speaker-fallback-gradient)"/>
    <text x="162" y="524" text-anchor="middle" font-size="88" font-weight="800" fill="#FFFFFF" font-family="'Avenir Next', Avenir, Montserrat, Arial, sans-serif">${escapeXml(initials)}</text>
  </g>`;

  return `<svg xmlns="http://www.w3.org/2000/svg" width="1080" height="1350" viewBox="0 0 1080 1350" fill="none">
  <defs>
    <clipPath id="speaker-photo-clip">
      <circle cx="162" cy="488" r="118"/>
    </clipPath>
    <filter id="speaker-photo-shadow" x="28" y="354" width="268" height="268" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
      <feDropShadow dx="0" dy="14" stdDeviation="16" flood-color="#050B1C" flood-opacity="0.24"/>
    </filter>
    <linearGradient id="speaker-fallback-gradient" x1="44" y1="370" x2="280" y2="606" gradientUnits="userSpaceOnUse">
      <stop stop-color="#33466F"/>
      <stop offset="1" stop-color="#1B2A48"/>
    </linearGradient>
  </defs>
  <image href="${speakerBackgroundDataUri}" x="0" y="0" width="1080" height="1350" preserveAspectRatio="xMidYMid slice"/>
  ${photoStage}
  ${nameText}
  ${titleText}
  <text x="${titleX}" y="${detailY}" font-size="28" font-weight="500" fill="rgba(255,255,255,0.92)" font-family="'Avenir Next', Avenir, Montserrat, Arial, sans-serif">${escapeXml(entry.detailLabel)}</text>
</svg>`;
}

function speakerTitleLayout(title) {
  const length = title.trim().length;
  if (length >= 100) {
    return { lines: wrapText(title, 31, 5), fontSize: 31, lineHeight: 38 };
  }
  if (length >= 76) {
    return { lines: wrapText(title, 29, 4), fontSize: 33, lineHeight: 40 };
  }
  return { lines: wrapText(title, 27, 4), fontSize: 35, lineHeight: 42 };
}

function wrapText(text, maxCharacters, maxLines = 2) {
  const words = text.trim().split(/\s+/).filter(Boolean);
  const lines = [];
  let current = '';

  for (const word of words) {
    const candidate = `${current} ${word}`.trim();
    if (current !== '' && candidate.length > maxCharacters) {
      lines.push(current);
      current = word;
      if (lines.length >= maxLines - 1) break;
    } else {
      current = candidate;
    }
  }

  if (current) lines.push(current);
  const sliced = lines.filter(Boolean).slice(0, maxLines);

  if (sliced.length === maxLines) {
    const usedWords = sliced.join(' ').split(/\s+/).filter(Boolean).length;
    if (words.length > usedWords) {
      const last = sliced[maxLines - 1];
      sliced[maxLines - 1] = last.length > maxCharacters - 1 ? `${last.slice(0, Math.max(8, maxCharacters - 2))}…` : `${last}…`;
    }
  }

  return sliced.length ? sliced : [text];
}

function initialsFor(name) {
  return name.split(/\s+/).filter(Boolean).slice(0, 2).map((part) => part[0].toUpperCase()).join('');
}

function normalizeSpeakerName(name) {
  return name
    .replace(/&quot;/g, '"')
    .replace(/\b(dr|phd)\b\.?/gi, ' ')
    .replace(/["'.(),]/g, ' ')
    .replace(/\s+and\s+/gi, ' ')
    .replace(/\s+/g, ' ')
    .trim()
    .toLowerCase();
}

function resolveSpeakerPhotoDataUri(name) {
  const normalized = normalizeSpeakerName(name);
  const candidates = [normalized];

  if (speakerPhotoAliases.has(normalized)) {
    candidates.push(speakerPhotoAliases.get(normalized));
  }

  if (normalized.includes(' ')) {
    candidates.push(normalized.split(' ')[0] + ' ' + normalized.split(' ').slice(1).join(' '));
  }

  for (const candidate of candidates) {
    const url = speakerPhotoUrls.get(candidate);
    if (!url) continue;
    if (!photoDataUriCache.has(url)) {
      photoDataUriCache.set(url, fetchRemoteDataUri(url));
    }
    return photoDataUriCache.get(url);
  }

  return null;
}

function formatTime(time) {
  if (!time) return 'TBD';
  const [hh, mm] = time.split(':').map(Number);
  const suffix = hh >= 12 ? 'PM' : 'AM';
  const hour = ((hh + 11) % 12) + 1;
  return `${hour}:${String(mm).padStart(2, '0')} ${suffix}`;
}

function slug(value) {
  return value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
}

function unescapePhp(value) {
  return value.replace(/\\'/g, "'").replace(/\\"/g, '"').replace(/\\\\/g, '\\');
}

function toDataUri(filePath) {
  const ext = path.extname(filePath).toLowerCase();
  const mime = ext === '.png'
    ? 'image/png'
    : ext === '.svg'
      ? 'image/svg+xml'
      : 'application/octet-stream';
  const content = fs.readFileSync(filePath).toString('base64');
  return `data:${mime};base64,${content}`;
}

function fetchRemoteDataUri(url) {
  try {
    const buffer = execFileSync('curl', ['-Ls', url], { maxBuffer: 20 * 1024 * 1024 });
    const ext = path.extname(new URL(url).pathname).toLowerCase();
    const mime = ext === '.png'
      ? 'image/png'
      : ext === '.webp'
        ? 'image/webp'
        : 'image/jpeg';
    return `data:${mime};base64,${buffer.toString('base64')}`;
  } catch {
    return null;
  }
}

function escapeXml(value) {
  return value
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&apos;');
}
