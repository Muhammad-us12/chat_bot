import {
  Page,
  Layout,
  Text,
} from "@shopify/polaris";
import { TitleBar } from "@shopify/app-bridge-react";

export default function HomePage() {
  return (
    <Page narrowWidth>
      <TitleBar title="Dashboard" primaryAction={null} />
      <Layout>
        <Layout.Section>
          <Text variant="heading3xl" as="h2">Welcome to dashboard</Text>
          <Text variant="bodySm" as="p">more content is coming soon ...</Text>
        </Layout.Section>
      </Layout>
    </Page>
  );
}
