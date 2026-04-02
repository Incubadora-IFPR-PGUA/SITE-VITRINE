import { createMongoAbility } from "@casl/ability";
import { abilitiesPlugin } from "@casl/vue";

/** Regras padrão para usuário logado (admin ou sem ACL): permite tudo. */
const defaultRules = [{ action: "manage", subject: "all" }];

function normalizeRules(val) {
  if (Array.isArray(val) && val.length) return val;
  if (val === "admin" || val === true) return defaultRules;
  return defaultRules;
}

export default function (app) {
  const userAbilityRules = useCookie("userAbilityRules");
  const rules = normalizeRules(userAbilityRules.value);
  const initialAbility = createMongoAbility(rules);

  app.use(abilitiesPlugin, initialAbility, {
    useGlobalProperties: true,
  });
}
